$(document).ready(function() {
    var current_page = 1;
    var filters_applied = false;
    var report_table = '';

    refreshGridView(current_page);
    enableAutocomplete();

    if ($("body").find("#from_date") && $("body").find("#to_date")) {
        $(function () {
            $("body").on("dp.change", "#fromdate", function (e) {
                $("body").find("#todate").data("DateTimePicker").minDate(e.date);
            });
        });
    }

    // refresh the grid view of report
    $("body").on("click", "#filter_report", function() {
        var filter_found = false;

        $.each($("#report-filters").find("input, select"), function() {
            if ($(this).val()) {
                filter_found = true;
            }
        });

        if (filter_found) {
            filters_applied = true;
            current_page = 1;
            refreshGridView(current_page);
        }
        else {
            refreshGridView(1);
        }
    });

    // refresh grid view if record length is changed
    $('body').on("change", '[name="report-table_length"]', function() {
        refreshGridView(current_page);
    });

    // refresh grid view if search is changed
    $('body').on("input change", 'input[type="search"]', function() {
        if ($(this).val() == "") {
            current_page = 1;
            refreshGridView(current_page);
        }
    });

    // get records when click on pagination links
    $(document).on('click', '.origin-pagination a', function (e) {
        e.preventDefault();

        if ($(this).attr('href') != "#" && $(this).attr('href').indexOf('page=') >= 0) {
            current_page = $(this).attr('href').split('page=')[1];
            refreshGridView(current_page);
        }
    });

    // download the report
    $("body").on("click", ".download-report", function(e) {
        e.preventDefault();
        var filters = "";
        var format = $(this).data('format');

        $.each($("#report-filters").find("input, select"), function() {
            if ($(this).attr("name") && $(this).val()) {
                filters += '&filters[' + $(this).attr("name") + ']=' + encodeURIComponent($(this).val().toString());
            }
        });

        window.location = app_route + "?download=Yes&format=" + format + filters;
    });

    function refreshGridView(page) {
        $("body").find(".data-loader").show();
        var filters = getReportFilters();
        var per_page = $('body').find('[name="report-table_length"]').val();

        $.ajax({
            type: 'GET',
            url: app_route + '?page=' + page,
            data: { 'filters': filters, 'per_page': per_page },
            dataType: 'json',
            success: function(data) {
                if (!(report_table instanceof $.fn.dataTable.Api)) {
                    createTableHeaders(data['columns']);
                }

                var grid_rows = data['rows']['data'];
                var from = data['rows']['from'] ? data['rows']['from'] : 0;
                var to = data['rows']['to'] ? data['rows']['to'] : 0;
                var total = data['rows']['total'];
                var columns = data['columns'];
                var rows = [];
                var table_rows = [];

                $.each(grid_rows, function(grid_index, grid_data) {
                    var row = {};

                    $.each(columns, function(idx, column) {
                        row[column] = grid_data[column];
                    });

                    row['id'] = grid_data['id'];
                    rows.push(row);
                });

                // clear the datatable
                report_table.clear().draw();

                if (rows.length > 0) {
                    // add each row to datatable using api
                    $.each(rows, function(grid_index, grid_data) {
                        var record = [];
                        record.push(grid_index == 0 ? from : from + grid_index);

                        $.each(grid_data, function(column_name, column_value) {
                            var form_link = base_url + '/form/' + data["module_slug"];

                            if (typeof column_value == "string" || typeof column_value == "number" || column_value === null) {
                                if (data['module'] && data['link_field'] && data['form_title'] && (data['form_title'] == column_name) && column_value) {
                                    column_value = '<a href="' + form_link + '/' + grid_data[data["link_field"]] + '">' + column_value + '</a>';
                                }
                                else if (column_value && typeof column_value == "string" && trim(column_value).isURL()) {
                                    column_value = '<a href="' + column_value + '" target="_blank">' + column_value + '</a>';
                                }

                                if (['image', 'photo', 'picture', 'profile_picture', 'profile_photo', 'logo', 'avatar'].contains(column_name)) {
                                    if (column_value) {
                                        img_path = getImage(column_value, 32, 32, 95, 0, 'b');
                                        column_value = '<div class="text-center"><img src="' + img_path + '" data-big="' + getImage(column_value) + '" class="fancyimg" alt="' + grid_data[data["form_title"]] + '"></div>';
                                    }
                                    else {
                                        if (data['module'] == 'User') {
                                            var default_icon = 'fas fa-user';
                                        }
                                        else {
                                            var default_icon = 'fas fa-image';
                                        }

                                        column_value = '<div class="text-center">\
                                            <span class="default-picture default-picture-rounded">\
                                                <i class="' + default_icon + '"></i>\
                                            </span>\
                                        </div>';
                                    }
                                }
                            }

                            if (columns.contains(column_name)) {
                                if (typeof column_value === 'string' && (column_value.isDate() || column_value.isDateTime() || column_value.isTime())) {
                                    if (column_value.isDate()) {
                                        column_value = moment.utc(column_value).local().format('DD-MM-YYYY');
                                    }
                                    else if (column_value.isDateTime()) {
                                        column_value = moment.utc(column_value).local().format('DD-MM-YYYY hh:mm A');
                                    }
                                    else {
                                        column_value = moment.utc('0001-01-01 ' + column_value).local().format('hh:mm A');
                                    }
                                }

                                record.push(column_value);
                            }
                        });

                        table_rows.push(record);
                    });

                    // add multiple rows to datatable using api
                    report_table.rows.add(table_rows).draw('false');

                    var report_info = '<span class="item-from">' + from + '</span> -\
                    <span class="item-to">' + to + '</span> of \
                    <span class="badge badge-primary item-count">' + total + '</span>';

                    $('body').find('.not-found').hide();
                    $("body").find(".report-actions").show();
                    $("body").find("#report-table_wrapper").show();
                    $("body").find(".list-actions").show();
                    $("body").find("#report-table_info").html(report_info);
                    $("body").find("#report-table_paginate").empty().append(makePagination(data['rows']));
                    report_table.columns.adjust();
                    enableFancyBox();
                }
                else {
                    $("body").find(".report-actions").hide();
                    $("body").find("#report-table_wrapper").hide();
                    $("body").find(".list-actions").hide();

                    if (Object.keys(filters).length) {
                        $('body').find('.not-found').html(getNoResults());
                    }
                    else {
                        if (typeof data['module_new_record'] !== 'undefined') {
                            var new_form = '<a href="' + data["module_new_record"] + '" class="btn bg-gradient-primary btn-sm elevation-2 new-form" data-toggle="tooltip" data-placement="bottom" title="New ' + data["module_name"] + '">\
                                <i class="fas fa-plus fa-sm pr-1"></i>\
                                New ' + data["module_name"] + '\
                            </a>';

                            var add_new = getAddNewRecord(data['module_name'], new_form);
                            $('body').find('.not-found').html(add_new);
                        }
                        else {
                            $('body').find('.not-found').html(getNoResults(data['module_name']));
                        }
                    }

                    $('body').find('.not-found').show();
                }

                $("body").find(".data-loader").hide();
            },
            error: function(e) {
                if (typeof JSON.parse(e.responseText)['message'] !== 'undefined') {
                    var error_msg = JSON.parse(e.responseText)['message'];
                }
                else {
                    var error_msg = 'Some error occured. Please try again';
                }

                notify(error_msg, "error");
                $("body").find(".data-loader").hide();
            }
        });
    }

    // append columns to table headers and initiliaze datatables
    function createTableHeaders(columns) {
        var headers = '<tr>\
            <th>#</th>';

        $.each(columns, function(idx, column) {
            var label = column.replace(/_/g, " ");
            label = label.toProperCase();
            label = label.replace("Id", "ID");

            headers += '<th name="' + column + '">' + label + '</th>';
        });

        headers += '</tr>';

        $('#report-table').find('thead').empty().append(headers);

        report_table = $('#report-table').DataTable({
            "bProcessing": true,
            "sDom": "<'row report-actions'<'col-sm-6'l><'col-sm-6'f>r>t",
            "iDisplayLength": 50,
            "sPaginationType": "full_numbers",
            "bAutoWidth": false,
            "oLanguage": {
                "sEmptyTable": "No Data",
                "sProcessing": '<div class="text-center>Processing...</div>'
            },
            "scrollY": 380,
            "scrollX": true
        });
    }

    // returns the filters for report
    function getReportFilters() {
        var filters = {};

        $.each($("#report-filters").find("input, select"), function() {
            if ($(this).attr("name") && $(this).val()) {
                filters[$(this).attr("name")] = $(this).val();
            }
        });

        if (Object.keys(filters).length > 0) {
            filters_applied = true;
        }
        else {
            filters_applied = false;
        }

        return filters;
    }

    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');

            if (page == Number.NaN || page <= 0) {
                return false;
            }
            else {
                refreshGridView(page);
            }
        }
    });
});