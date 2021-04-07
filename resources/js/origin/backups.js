$(document).ready(function() {
    var current_page = 1;
    var recently_deleted = false;

    refreshBackups(current_page);

    $("body").on("click", ".refresh-backups", function() {
        current_page = 1;
        refreshBackups(current_page);
    });

    $("body").on("click", ".delete-backup",  function() {
        var href = $(this).data("href");

        var modal_footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>\
            <button type="button" class="btn btn-danger btn-sm confirm-delete-backup" data-href="' + href + '">Yes</button>';

        msgbox("Sure you want to delete this backup permanently?", modal_footer);
    });

    $("body").on("click", ".confirm-delete-backup", function() {
        var href = $(this).data("href");

        $.ajax({
            type: 'POST',
            url: href,
            dataType: 'json',
            success: function(data) {
                $("body").find("#message-box").modal('hide');

                if (data['success']) {
                    recently_deleted = true;
                    refreshBackups(current_page);
                }
                else {
                    notify(data['msg'], "error");
                }
            },
            error: function(e) {
                if (typeof JSON.parse(e.responseText)['message'] !== 'undefined') {
                    var error_msg = JSON.parse(e.responseText)['message'];
                }
                else {
                    var error_msg = 'Some error occured. Please try again';
                }

                notify(error_msg, "error");
                $("body").find("#message-box").modal('hide');
            }
        });
    });

    $("body").on("click", ".create-backup", function(e) {
        e.preventDefault();
        var me = this;
        var href = $(this).data("href");
        $("body").find(".data-loader-full").show();

        $.ajax({
            type: 'POST',
            url: href,
            dataType: 'json',
            success: function(data) {
                $("body").find(".data-loader-full").hide();

                if (data['success']) {
                    notify(data['msg'], "info");
                    refreshBackups(current_page);
                }
                else {
                    notify(data['msg'], "error");
                }
            },
            error: function(e) {
                if (typeof JSON.parse(e.responseText)['message'] !== 'undefined') {
                    var error_msg = JSON.parse(e.responseText)['message'];
                }
                else {
                    var error_msg = 'Some error occured. Please try again';
                }

                notify(error_msg, "error");
                $("body").find(".data-loader-full").hide();
            }
        });
    });

    // get records when click on pagination links
    $('body').on('click', '.origin-pagination a', function (e) {
        e.preventDefault();

        if ($(this).attr('href') != "#" && $(this).attr('href').indexOf('page=') >= 0) {
            current_page = $(this).attr('href').split('page=')[1];
            refreshBackups(current_page);
        }
    });

    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');

            if (page == Number.NaN || page <= 0) {
                return false;
            }
            else {
                refreshBackups(page);
            }
        }
    });

    // refresh backups
    function refreshBackups(page, delete_list) {
        if (!recently_deleted) {
            $("body").find(".data-loader").show();
        }

        $.ajax({
            type: 'GET',
            url: app_route + '?page=' + page,
            dataType: 'json',
            success: function(data) {
                recently_deleted = false;
                var list_columns = ['name', 'date', 'size', 'type', 'download', 'delete'];
                var list_rows = data['backups']['data'];
                var list_table = $("body").find(".list-view");
                var list_records = "";

                if (Object.keys(list_rows).length > 0) {
                    $.each(list_rows, function(index, row_data) {
                        list_records += '<tr class="clickable_row">\
                            <td class="text-center">' + (parseInt(index) + 1) + '</td>';

                        $.each(list_columns, function(idx, column_name) {
                            var field_value = row_data[column_name];

                            if (column_name == "download") {
                                list_records += '<td data-field-name="' + column_name + '">\
                                    <a href="' + field_value + '" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Download backup">\
                                        Download\
                                    </a>\
                                </td>';
                            }
                            else if (column_name == "delete") {
                                list_records += '<td data-field-name="' + column_name + '">\
                                    <button class="btn btn-danger btn-xs delete-backup" data-toggle="tooltip" data-placement="bottom" title="Delete backup" data-href="' + field_value + '">\
                                        Delete\
                                    </button>\
                                </td>';
                            }
                            else if (column_name == "type") {
                                if (field_value == "Database") {
                                    list_records += '<td data-field-name="' + column_name + '">\
                                        <span class="label label-info">' + field_value + '</span>\
                                    </td>';
                                }
                                else if (field_value == "Files") {
                                    list_records += '<td data-field-name="' + column_name + '">\
                                        <span class="label label-warning">' + field_value + '</span>\
                                    </td>';
                                }
                                else if (field_value == "Database + Files") {
                                    list_records += '<td data-field-name="' + column_name + '">\
                                        <span class="label label-primary">' + field_value + '</span>\
                                    </td>';
                                }
                            }
                            else {
                                list_records += '<td data-field-name="' + column_name + '">' + field_value + '</td>';
                            }
                        });

                        list_records += '</tr>';
                    });
                }
                else {
                    var new_form = $('body').find('.new-backup').clone().wrap("<div />").parent().html();
                    var add_new = getAddNewRecord('Backups', new_form);

                    list_records = '<tr class="no-results">\
                        <td colspan="' + (list_columns.length + 1) + '" class="dataTables_empty">' + add_new + '</td>\
                    </tr>';
                }

                $(list_table).find('.list-view-items').empty().append(list_records);
                $("body").find(".item-count").html(data['backups']['total'] || '0');
                $("body").find(".item-from").html(data['backups']['from'] || '0');
                $("body").find(".item-to").html(data['backups']['to'] || '0');

                $("body").find(".origin-pagination-content").empty().append(makePagination(data['backups']));
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
});