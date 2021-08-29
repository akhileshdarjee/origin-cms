$(document).ready(function() {
    var current_page = 1;
    var search_timer;
    var report_table = false;
    var report_columns = [];
    var data_loaded = false;

    refreshGridView(current_page);
    enableAutocomplete();

    if ($('body').find("#from_date") && $('body').find("#to_date")) {
        $(function () {
            $('body').on("dp.change", "#fromdate", function (e) {
                $('body').find("#todate").data("DateTimePicker").minDate(e.date);
            });
        });
    }

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

    // refresh table data on individual column search
    $('body').on("keyup", '.column-search', function() {
        current_page = 1;
        clearTimeout(search_timer);
        search_timer = setTimeout(refreshGridView, 450);
    });

    $('body').on("keydown", '.column-search', function() {
        clearTimeout(search_timer);
    });

    // refresh table data on individual column sorting
    $('#report-table').on('order.dt', function(e) {
        if (data_loaded) {
            e.preventDefault();
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
    $('body').on("click", ".download-report", function(e) {
        e.preventDefault();
        var filters = $.param({'filters': getReportFilters()});
        var format = $(this).data('format');
        var filters_section = $('body').find('.report-columns-search');

        window.location = app_route + "?download=Yes&format=" + format + "&" + filters;
    });

    function refreshGridView(page) {
        $('body').find(".data-loader").show();
        var filters = getReportFilters();
        var per_page = $('body').find('[name="report-table_length"]').val();
        page = page ? page : current_page;

        $.ajax({
            type: 'GET',
            url: app_route + '?page=' + page,
            data: {'filters': filters, 'per_page': per_page},
            dataType: 'json',
            success: function(data) {
                report_columns = data['columns'];

                if (!(report_table instanceof $.fn.dataTable.Api)) {
                    createTableHeaders();
                }

                var grid_rows = data['rows']['data'];
                var from = data['rows']['from'] ? data['rows']['from'] : 0;
                var to = data['rows']['to'] ? data['rows']['to'] : 0;
                var total = data['rows']['total'];
                var page_no = data['rows']['current_page'] ? data['rows']['current_page'] : 0;
                var rows = [];
                var table_rows = [];

                $.each(grid_rows, function(grid_index, grid_data) {
                    var row = {};

                    $.each(report_columns, function(idx, column) {
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
                                else if (column_value && typeof column_value == "string" && $.trim(column_value).isURL()) {
                                    column_value = '<a href="' + column_value + '" target="_blank">' + column_value + '</a>';
                                }

                                if (['image', 'photo', 'picture', 'profile_picture', 'profile_photo', 'logo', 'avatar'].contains(column_name)) {
                                    if (column_value) {
                                        img_path = getImage(column_value, 32, 32, 95, 0, 'b');

                                        column_value = '<div class="text-center">\
                                            <img src="' + img_path + '" data-big="' + getImage(column_value) + '" class="fancyimg" alt="' + grid_data[data["form_title"]] + '">\
                                        </div>';
                                    }
                                    else {
                                        column_value = '<div class="text-center">';

                                        if (data['module'] == 'User') {
                                            column_value += '<div class="avatar-initials avatar-initials-xs avatar-initials-circle" data-name="' + grid_data["full_name"] + '"></div>';
                                        }
                                        else {
                                            column_value += '<span class="default-picture default-picture-rounded">\
                                                <i class="fas fa-image"></i>\
                                            </span>';
                                        }

                                        column_value += '</div>';
                                    }
                                }
                            }

                            if (report_columns.contains(column_name)) {
                                if (typeof column_value === 'string' && (column_value.isDate() || column_value.isDateTime() || column_value.isTime())) {
                                    if (column_value.isDate()) {
                                        column_value = moment.utc(column_value).tz(origin.time_zone).format('DD-MM-YYYY');
                                    }
                                    else if (column_value.isDateTime()) {
                                        column_value = moment.utc(column_value).tz(origin.time_zone).format('DD-MM-YYYY hh:mm A');
                                    }
                                    else {
                                        column_value = moment.utc('0001-01-01 ' + column_value).tz(origin.time_zone).format('hh:mm A');
                                    }
                                }

                                record.push(column_value);
                            }
                        });

                        table_rows.push(record);
                    });

                    // add multiple rows to datatable using api
                    report_table.rows.add(table_rows).draw('false');

                    var report_info = __('Page') + ':\
                        <span class="page-no indicator-pill indicator-primary no-indicator mr-1">' + page_no + '</span> • \
                        <span class="item-from ml-1">' + from + '</span> -\
                        <span class="item-to">' + to + '</span> ' + __("of") + ' \
                        <span class="indicator-pill indicator-primary no-indicator item-count">' + total + '</span>\
                        ' + __('records');

                    $('body').find('.not-found').hide();
                    $('body').find(".report-data").show();
                    $('body').find(".list-actions").show();
                    $('body').find(".dataTables_scrollBody").css({'overflow': 'auto', 'height': '450px'});
                    $('body').find(".report-columns-search").removeClass('no-border');
                    $('body').find("#report-table_info").html(report_info);
                    $('body').find("#report-table_paginate").empty().append(makePagination(data['rows']));

                    enableFancyBox();
                    createAvatarWithInitials();
                    addIndividualColumnSearching(data['module']);
                    report_table.columns.adjust().draw();
                    data_loaded = true;
                }
                else {
                    $('body').find(".report-data").hide();
                    $('body').find(".list-actions").hide();
                    $('body').find(".dataTables_scrollBody").css({'overflow': 'hidden', 'height': 'auto'});
                    $('body').find(".report-columns-search").addClass('no-border');

                    if (Object.keys(filters).length) {
                        $('body').find('.not-found').html(getNoResults());
                    }
                    else {
                        if (typeof data['module_new_record'] !== 'undefined') {
                            var new_form = '<a href="' + data["module_new_record"] + '" class="btn bg-gradient-primary btn-sm elevation-2 new-form" data-toggle="tooltip" data-placement="bottom" title="' + __("New") + ' ' + data["module_name"] + '">\
                                <i class="fas fa-plus fa-sm pr-1"></i>\
                                ' + __("New") + ' ' + data["module_name"] + '\
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

                $('body').find(".data-loader").hide();
            },
            error: function(e) {
                if (typeof JSON.parse(e.responseText)['message'] !== 'undefined') {
                    var error_msg = JSON.parse(e.responseText)['message'];
                }
                else {
                    var error_msg = __('Some error occured. Please try again');
                }

                notify(error_msg, "error");
                $('body').find(".data-loader").hide();
            }
        });
    }

    // append columns to table headers and initiliaze datatables
    function createTableHeaders() {
        var headers = '<tr>\
            <th>#</th>';

        $.each(report_columns, function(idx, column) {
            var label = column.replace(/_/g, " ");
            label = label.toProperCase();
            label = label.replace("Id", "ID");

            headers += '<th name="' + column + '">' + __(label) + '</th>';
        });

        headers += '</tr>';

        $('#report-table').find('thead').empty().append(headers);
        initDatatables();
    }

    // initialize Datatables
    function initDatatables() {
        report_table = $('#report-table').DataTable({
            "sDom": "<'row report-actions'<'col-sm-6'l><'col-sm-6'f>r>t",
            "bFilter": false,
            "aLengthMenu": [20, 50, 100],
            "iDisplayLength": 50,
            "sPaginationType": "full_numbers",
            "bAutoWidth": false,
            "oLanguage": {
                "sLengthMenu": __('Show') + " _MENU_ " + __('records'),
                "sSearch": "",
                "sSearchPlaceholder": __('Search in table'),
                "sEmptyTable": "",
                "sInfoEmpty": "",
                "sZeroRecords": "" 
            },
            "scrollY": 450,
            "scrollX": true
        });
    }

    // add search box for each column
    function addIndividualColumnSearching(module_name) {
        var table_search_columns = $('body').find('.report-columns-search');

        if (!$(table_search_columns).length) {
            var search_columns = '<tbody class="report-columns-search">\
                <tr class="even">\
                    <td></td>';

            $.each(report_columns, function(idx, column) {
                search_columns += '<td>\
                    <input type="text" name="' + column + '" class="form-control form-control-sm column-search" autocomplete="off">\
                </td>';
            });

            search_columns += '</tr>\
                </tbody>';

            $('body').find('#report-table tbody').before(search_columns);
        }
    }

    // returns the filters for report
    function getReportFilters() {
        var filters = {
            'columns': {},
            'sort': {}
        };

        var filters_section = $('body').find('.report-columns-search');

        $.each($(filters_section).find("input, select"), function() {
            if ($(this).attr("name") && $(this).val()) {
                filters['columns'][$(this).attr("name")] = $(this).val();
            }
        });

        if (report_table instanceof $.fn.dataTable.Api) {
            var order = report_table.order();

            if (order[0][0] > 0) {
                var col = report_columns[order[0][0] - 1];
                var dir = order[0][1];

                filters['sort'][col] = dir;
            }

            // data loaded is set to false because .order() hits ordering callback function which calls AJAX infinitely
            data_loaded = false;
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