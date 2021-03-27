$(document).ready(function() {
    var current_page = 1;
    var module_name = '';
    var recently_deleted = false;

    refreshListView(current_page);

    $("body").on("click", "#add-filter", function() {
        resetFilterInputs();
        $('.list-column-filters').toggle();
    });

    $("body").on("click", ".refresh-list-view", function() {
        current_page = 1;
        refreshListView(current_page);
    });

    // sort list view either ascending or descending
    $("body").on("click", "#sort-list-order", function() {
        var order = $(this).data("value");
        var btn_html = '';
        var sort_order = '';

        if (order == "desc") {
            sort_order = 'asc';
            btn_html = '<i class="fa fa-arrow-up"></i>';
        }
        else {
            sort_order = 'desc';
            btn_html = '<i class="fa fa-arrow-down"></i>';
        }

        $(this).html(btn_html);
        $(this).attr("data-value", sort_order);
        $(this).data("value", sort_order);

        updateSortingFields();
    });

    // sort list view by column name
    $("body").on("click", ".sort-list-by-name", function(e) {
        e.preventDefault();
        var sort_field = $(this).data("value");
        var field_label = $.trim($(this).html());

        $("body").find("#sort-field").attr("data-value", sort_field);
        $("body").find("#sort-field").data("value", sort_field);
        $("body").find("#sort-field").html(field_label);

        updateSortingFields();
    });

    // on row click show the record form view
    $(".list-view").on("click" , '.clickable_row', function(e) {
        if (!$(e.target).is("a")) {
            if ($(e.target).closest('td').attr('data-field-name') != "row_check" && e.target.type != "checkbox") {
                if (!$(e.target).is("img") && !$(e.target).hasClass('fancyimg')) {
                    window.location = $(this).data("href");
                }
            }
        }
    });

    // Check all checkboxes in list view on parent check
    $('body').on('change', '.list-view .list-header [type="checkbox"]', function(e) {
        e && e.preventDefault();
        var $table = $(e.target).closest('.list-view'), $checked = $(e.target).is(':checked');
        $('.list-view-items [type="checkbox"]', $table).prop('checked', $checked);
        toggleActionButton();
    });

    // toggle action button on list row check
    $('body').on('change', '.list-view .list-view-items [type="checkbox"]', function(e) {
        toggleActionButton();
    });

    $("body").on("click", ".delete-selected", function() {
        removeSelectedRowData();
    });

    $("body").on("click", ".remove-column-filters", function() {
        resetFilterInputs();
        $(this).closest('.list-column-filters').hide();
    });

    $("body").on("click", ".apply-column-filters", function() {
        var filter_container = $(this).closest('.list-column-filters');
        var column_name = $(filter_container).find('[name="column_name"]').val();
        var column_operator = $(filter_container).find('[name="column_operator"]').val();
        var column_value = $(filter_container).find('[name="column_value"]').val();
        var column_value_label = column_value;

        if ($(filter_container).find('[name="column_value"]').is('select')) {
            column_value_label = $(filter_container).find('[name="column_value"] option:selected').text();
        }

        if (column_name && column_operator) {
            var column_label = $(filter_container).find('[name="column_name"] option:selected').text();
            var tag_text = column_label + ' ' + column_operator + ' ' + (column_value_label || "Null");

            var filter_tag = '<div class="btn-group filter-tag" data-cn="' + column_name + '" data-co="' + column_operator + '" data-cv="' + column_value + '">\
                <button class="btn btn-white btn-xs" type="button">' + tag_text + '</button>\
                <button class="btn btn-white btn-xs remove-filter" type="button" data-toggle="tooltip" data-placement="right" title="Remove filter">\
                    <i class="fa fa-times"></i>\
                </button>\
            </div>';

            $('body').find('.list-active-filters').show();
            $('body').find('.list-active-filters').append(filter_tag);

            resetFilterInputs();
            $(this).closest('.list-column-filters').hide();

            current_page = 1;
            refreshListView(current_page);
        }
        else {
            notify("Please select Column Name & Column Operator", "error");
        }
    });

    $('.list-column-filters').find('[name="column_name"]').on("change", function() {
        var column_name = $(this).find(":selected").val();
        var column_type = $(this).find(":selected").data("type");
        var filter_sec = $(this).closest('.list-column-filters');
        var value_container = $(filter_sec).find('.column-value-container');
        var new_input = '';

        if (column_type == "boolean") {
            new_input = '<select class="form-control" name="column_value" data-toggle="tooltip" data-placement="bottom" title="Filter value">\
                <option value="1">Yes</option>\
                <option value="0">No</option>\
            </select>';
        }
        else if (column_type == "date") {
            new_input = '<div class="input-group">\
                <span class="input-group-addon">\
                    <i class="fa fa-calendar"></i>\
                </span>\
                <input type="text" name="column_value" class="form-control date" autocomplete="off" data-toggle="tooltip" data-placement="bottom" title="Filter value">\
            </div>';
        }
        else if (column_type == "datetime") {
            new_input = '<div class="input-group datetimepicker">\
                <span class="input-group-addon">\
                    <i class="fa fa-calendar"></i>\
                </span>\
                <input type="text" name="column_value" class="form-control date" autocomplete="off" data-toggle="tooltip" data-placement="bottom" title="Filter value">\
            </div>';
        }
        else {
            new_input = '<input type="text" name="column_value" class="form-control autocomplete" autocomplete="off" data-ac-module="' + module_name + '" data-ac-field="' + column_name + '" data-ac-unique="Yes" data-toggle="tooltip" data-placement="bottom" title="Filter value">';
        }

        $(value_container).empty().append(new_input);

        if (column_type == "date") {
            enableDatepicker();
        }
        else if (column_type == "datetime") {
            enableDateTimepicker();
        }
        else if (column_type != "boolean") {
            enableAutocomplete();
        }
    });

    // remove filter tag and refresh list view
    $('body').on('click', '.filter-tag .remove-filter', function (e) {
        $(this).tooltip('hide');
        $(this).closest('.filter-tag').remove();
        refreshListView(current_page);
    });

    // get records when click on pagination links
    $('body').on('click', '.origin-pagination a', function (e) {
        e.preventDefault();

        if ($(this).attr('href') != "#" && $(this).attr('href').indexOf('page=') >= 0) {
            current_page = $(this).attr('href').split('page=')[1];
            refreshListView(current_page);
        }
    });

    // import data from csv
    $("body").on("click", "#import-from-csv", function() {
        var title = $(this).attr("title");

        if (!title) {
            title = $(this).data("original-title");
        }

        var import_form = '<div class="row">\
            <div class="col-md-12">\
                <form method="POST" name="import-form" id="import-form" enctype="multipart/form-data">\
                    <div class="row">\
                        <div class="col-md-12">\
                            <div class="form-group text-center">\
                                <label class="control-label">Import File (.csv, .xls, .xlsx)</label><br>\
                                <label title="Upload file" for="import_file" class="btn btn-primary btn-sm">\
                                    <input type="file" accept=".csv, .xls, .xlsx" name="import_file" id="import_file" class="hide">\
                                    Change\
                                </label>\
                                <input type="hidden" class="form-control" name="module" value="' + $(this).data("module") + '">\
                                <div id="import-file-name"></div>\
                            </div>\
                        </div>\
                    </div>\
                    <div class="row">\
                        <div class="col-md-12">\
                            <div class="import-progress progress progress-sm active" style="display: none;">\
                                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">\
                                </div>\
                            </div>\
                            <button type="button" class="btn btn-block btn-primary" id="start-importing">Import</button>\
                        </div>\
                    </div>\
                </form>\
            </div>\
        </div>';

        msgbox(import_form, null, title);

        // show file name on change
        $("#import_file").on("change", function() {
            $("#import-file-name").html(document.getElementById("import_file").files[0].name);
        });

        // start importing by submitting the form
        $("#start-importing").on("click", function() {
            var me = this;
            var import_form = $(this).closest("#import-form");
            $(import_form).find('.progress').show();
            $(me).hide();

            if (document.getElementById("import_file").files[0].name) {
                var data = new FormData($(import_form)[0]);

                $.ajax({
                    url: base_url + '/import_from_csv',
                    type: 'POST',
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data['success']) {
                            location.reload();
                        }
                        else {
                            $(import_form).find('.progress').hide();
                            $(me).show();
                            notify(data['msg'], "error");
                        }
                    },
                    error: function (data) {
                        $(import_form).find('.progress').hide();
                        $(me).show();
                        notify("Some internal error occured. Please try again...!!!", "error");
                    }
                });
            }
        });
    });

    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');

            if (page == Number.NaN || page <= 0) {
                return false;
            }
            else {
                refreshListView(page);
            }
        }
    });

    // refresh table rows
    function refreshListView(page, delete_list) {
        var data = getSortingFiltersData();

        if (delete_list) {
            data['delete_list'] = delete_list;
        }
        else if (!recently_deleted) {
            $("body").find(".data-loader").show();
        }

        $.ajax({
            type: 'GET',
            url: app_route + '?page=' + page,
            data: data,
            dataType: 'json',
            success: function(data) {
                if (delete_list) {
                    processPostDelete(data['data']);
                }
                else {
                    recently_deleted = false;
                    var list_columns = data['columns'];
                    var list_rows = data['rows']['data'];
                    var link_field = data['module']['link_field'];
                    module_name = data['module']['name'];
                    var form_title = data['module']['form_title'];
                    var number_start = data['rows']['from'];

                    var list_table = $("body").find(".list-view").attr("data-module", module_name);
                    var list_records = "";
                    var form_link = app_route.replace("/list/", "/form/");

                    if (list_rows.length > 0) {
                        $.each(list_rows, function(index, row_data) {
                            var record_link = form_link + '/' + row_data[link_field];

                            list_records += '<tr class="clickable_row" data-href="' + record_link + '" data-id="' + row_data["id"] + '">';

                            if (data['can_delete']) {
                                list_records += '<td width="10%" data-field-name="row_check" class="list-checkbox">\
                                    <input type="checkbox" name="post[]" value="' + (index + 2) + '" id="check-' + (index + 2) + '">\
                                </td>';
                            }
                            else {
                                list_records += '<td class="text-center">' + (number_start + index) + '</td>';
                            }

                            $.each(list_columns, function(idx, column_name) {
                                var field_value = row_data[column_name];

                                if (!field_value && field_value != 0) {
                                    field_value = '';
                                }

                                if (['avatar', 'image'].contains(column_name)) {
                                    if (field_value) {
                                        list_records += '<td data-field-name="' + column_name + '" class="client-avatar">\
                                            <img src="' + getImage(field_value, "32", "32") + '" class="fancyimg img-circle" alt="' + row_data[form_title] + '" data-big="' + getImage(field_value) + '">\
                                        </td>';
                                    }
                                    else {
                                        list_records += '<td data-field-name="' + column_name + '" class="client-avatar">\
                                            <span class="default-picture">\
                                                <i class="fa fa-picture-o"></i>\
                                            </span>\
                                        </td>';
                                    }
                                }
                                else if (column_name == form_title) {
                                    list_records += '<td data-field-name="' + column_name + '" class="link-field">\
                                        <a href="' + record_link + '" class="form-link">' + field_value + '</a>\
                                    </td>';
                                }
                                else {
                                    list_records += '<td data-field-name="' + column_name + '">' + field_value + '</td>';
                                }
                            });

                            list_records += '</tr>';
                        });
                    }

                    $(list_table).find('.list-view-items').empty().append(list_records);
                    $("body").find(".list-page-no").html(data['rows']['current_page'] || '0');
                    $("body").find(".item-count").html(data['rows']['total'] || '0');
                    $("body").find(".item-from").html(data['rows']['from'] || '0');
                    $("body").find(".item-to").html(data['rows']['to'] || '0');
                    $("body").find(".origin-pagination-content").empty().append(makePagination(data['rows']));

                    toggleActionButton();
                    beautifyListView();

                    $("body").find(".data-loader").hide();
                }
            },
            error: function(e) {
                notify('Some error occured. Please try again', "error");
                $("body").find(".data-loader").hide();
            }
        });
    }

    // show delete button if any record is selected or show new
    function toggle_action_button() {
        var list_items = $(".list-view").find(".list-view-items");
        var checked_length = $(list_items).find("input[type='checkbox']:checked").length;

        toggle_check_all_box(checked_length);

        if (checked_length > 0) {
            var total_records = $("#item-count").html();
            var selected_msg = checked_length + ' of ' + total_records + ' selected';
            $("body").find('.record-selected-count').html(selected_msg);
            $("body").find('.record-selected-count').show();
            $("body").find(".new-form").hide();
            $("body").find(".delete-selected").show();
        }
        else {
            $("body").find('.record-selected-count').html('');
            $("body").find('.record-selected-count').hide();
            $("body").find(".new-form").show();
            $("body").find(".delete-selected").hide();
        }
    }

    // show delete button if any record is selected or show new
    function toggleActionButton() {
        var list_items = $(".list-view").find(".list-view-items");
        var checked_length = $(list_items).find("input[type='checkbox']:checked").length;

        toggleCheckAllBox(checked_length);

        if (checked_length > 0) {
            var total_records = $("body").find(".item-count").html();
            var selected_msg = checked_length + ' of ' + total_records + ' selected';
            $("body").find('.record-selected-count').html(selected_msg);
            $("body").find('.record-selected-count').show();
            $("body").find(".new-form").hide();
            $("body").find(".delete-selected").show();
        }
        else {
            $("body").find('.record-selected-count').html('');
            $("body").find('.record-selected-count').hide();
            $("body").find(".new-form").show();
            $("body").find(".delete-selected").hide();
        }
    }

    // delete all records based on rows checked
    function removeSelectedRowData() {
        var checked_rows = getCheckedRows();

        if (checked_rows.length > 0) {
            var modal_footer = '<button type="button" class="btn btn-sm" data-dismiss="modal">No</button>\
                <button type="button" class="btn btn-danger btn-sm" id="delete-records" data-loading-text="Deleting...">Yes</button>';
            msgbox("Sure you want to delete selected records permanently?", modal_footer);
        }
        else {
            notify("Please select any record to delete", "error");
        }

        $("#delete-records").on("click", function() {
            refreshListView(current_page, checked_rows);
        });
    }

    // get all checked rows
    function getCheckedRows() {
        var checked_rows = [];
        var list_items = $(".list-view").find(".list-view-items");

        $.each($(list_items).find("input[type='checkbox']"), function(index, element) {
            if ($(element).is(":checked")) {
                checked_rows.push($(this).closest('.clickable_row').data('id'));
            }
        });

        return checked_rows;
    }

    // toggle check all checkbox
    function toggleCheckAllBox(checked_length) {
        var list_items = $(".list-view").find(".list-view-items");
        var total_check_boxes = $(list_items).find("input[type='checkbox']").length;

        if (!checked_length) {
            var checked_length = $(list_items).find("input[type='checkbox']:checked").length;
        }

        if (checked_length && checked_length == total_check_boxes) {
            $('body').find('#check-all').prop('checked', true);
        }
        else {
            $('body').find('#check-all').prop('checked', false);
        }
    }

    // prepare all filters and sorting data
    function getSortingFiltersData() {
        var data = {};
        var filters = [];
        var sort_order = $("body").find("#sort-list-order").data("value");
        var sort_field = $("body").find("#sort-field").data("value");

        data['sorting'] = {'field': sort_field, 'order': sort_order};

        var active_filters = $('body').find('.list-active-filters .filter-tag');

        if (active_filters.length) {
            $.each($(active_filters), function(idx, filter) {
                var column_name = $(filter).data('cn');
                var column_operator = $(filter).data('co');
                var column_value = $(filter).data('cv');

                if (column_name && column_operator) {
                    current_filters = {
                        'column_name': column_name,
                        'column_operator': column_operator,
                        'column_value': column_value
                    };

                    filters.push(current_filters);
                }
            });

            data['filters'] = filters;
        }

        return data;
    }

    // show error or success notification based on delete result
    function processPostDelete(result) {
        $("body").find("#message-box").modal('hide');
        var error_data = [];

        $.each(result, function(idx, res) {
            if (!res['success']) {
                error_data.push(res['msg']);
            }
        });

        if (error_data && error_data.length) {
            $.each(error_data, function(idx, err_msg) {
                notify(err_msg, "error");
            });
        }
        else {
            notify("Records deleted successfully", "success");
        }

        if (result.length == error_data.length) {
            $("body").find(".data-loader").hide();
        }
        else {
            recently_deleted = true;
            refreshListView(current_page);
        }
    }

    // reset all input, select, etc. elements to default
    function resetFilterInputs() {
        var filter_sec = $('body').find('.list-column-filters');
        var column_name = $(filter_sec).find('[name="column_name"] option:first').val();
        var column_operator = $(filter_sec).find('[name="column_operator"] option:first').val();

        $(filter_sec).find('[name="column_name"]').val(column_name);
        $(filter_sec).find('[name="column_operator"]').val(column_operator);
        $('.list-column-filters').find('[name="column_name"]').trigger("change");
    }

    // update sort order and sort field for current module
    function updateSortingFields() {
        var sort_order = $("body").find("#sort-list-order").data("value");
        var sort_field = $("body").find("#sort-field").data("value");
        var data_action = $("body").find(".sorting-fields").data("action");

        if (sort_order && sort_field && data_action) {
            $("body").find(".data-loader").show();

            $.ajax({
                type: 'POST',
                url: data_action,
                data: {'sort_order': sort_order, 'sort_field': sort_field, 'module': module_name},
                dataType: 'json',
                success: function(data) {
                    $("body").find(".data-loader").hide();

                    if (data['success']) {
                        refreshListView(current_page);
                    }
                    else {
                        notify(data['msg'], "error");
                    }
                },
                error: function(e) {
                    notify('Some error occured. Please try again', "error");
                    $("body").find(".data-loader").hide();
                }
            });
        }
    }
});