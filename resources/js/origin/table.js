$(document).ready(function() {
    $("body").on("click", ".new-row", function() {
        var table = $("#" + $(this).data("target"));
        addNewRow(table);
        $(table).find("tr:last > td:eq(3) > input").focus();
    });

    // remove row
    $("table").on("click" , '.remove-row', function() {
        var child_table = $(this).closest("table");
        var target = $(child_table).attr("id");
        var tbody = $(child_table).find("tbody");
        var table_name = $(child_table).data("table");
        var row_id = $(this).closest('.table_record').find('.row-id');
        row_id = $(row_id).find('input[name$="[id]"]');

        if ($('body').find('[name="id"]').val() && $(row_id).val()) {
            // if ID is present, means the record already exists in db
            // At that time if row is deleted then set action as delete and hide the row
            $(this).closest("tr.table_record").find("td.action").find("input").val("delete");
            $(this).closest("tr.table_record").hide();
        }
        else {
            // if ID is not present then simply remove row which will be independent of action
            $(this).closest("tr").remove();
        }

        if ($(tbody).find("tr:visible").length) {
            maintainIdx(tbody);
        }
        else {
            showEmptyRow(child_table);
        }

        enableSaveButton();
    });

    // make row editable
    $("table").on("click", '.table_record', function() {
        $(this).find("input").removeClass("simple-box");
    });

    // set action update if input is changed
    $("table > tbody > tr").on("change", 'input, select, textarea', function() {
        if ($('[name="id"]').val()) {
            $(this).closest("tr").find("td.action > input").val("update");
        }

        if ($(this).attr("type") == "checkbox") {
            if (this.checked) {
                $(this).parent().find('.checkbox-value').val('1');
            }
            else {
                $(this).parent().find('.checkbox-value').val('0');
            }
        }
    });
});

function addNewRow(table, idx, action) {
    var thead = $(table).find("thead");
    var tbody = $(table).find("tbody");

    // remove empty row
    if ($(tbody).find("tr").hasClass("odd")) {
        $(tbody).empty();
    }

    // add row html
    addRow(table, idx ? idx : $(tbody).find("tr").length + 1, action);
}

function addRow(table, idx, action) {
    var table_name = $(table).data("table");
    var thead = $(table).find("thead");
    var tbody = $(table).find("tbody");
    var row_action = action ? action : "create";
    var field_types = [];

    var row = '<tr class="table_record">';

    $.each($(thead).find("tr > th"), function(index, heads) {
        if ($(heads).hasClass('sr-no') && index == 0) {
            row += '<td class="text-center" style="vertical-align: middle;"></td>';
        }
        else if ($(heads).hasClass('remove')) {
            row += '<td class="text-center" data-idx="' + idx + '" style="vertical-align: middle;">\
                <button type="button" class="btn btn-danger btn-xs remove-row">\
                    <i class="fas fa-times p-1"></i>\
                </button>\
            </td>';
        }
        else if ($(heads).hasClass('action')) {
            row += '<td class="action" style="display: none;">\
                <input type="text" class="form-control form-control-sm" name="' + table_name + '[' + (idx - 1) + '][action]" value="' + row_action + '">\
            </td>';

            $(this).find('input[name="' + table_name + '[' + (idx - 1) + '][action]"]').val(row_action);
        }
        else if ($(heads).hasClass('row-id')) {
            row += '<td class="row-id" style="display: none;">\
                <input type="text" class="form-control form-control-sm" name="' + table_name + '[' + (idx - 1) + '][id]">\
            </td>';
        }
        else {
            var field_type = $(heads).data("field-type");
            var field_name = $(heads).data("field-name");
            var target_module = $(heads).data("ac-module");
            var target_field = $(heads).data("ac-field");
            var readonly = ($(heads).data("readonly") == "yes") ? "readonly" : "";
            var hidden = ($(heads).data("hidden") == "yes") ? "style='display: none;'" : "";

            field_types.push(field_type);

            if (field_type == "link") {
                row += '<td data-field-type="link">\
                    <input type="text" class="form-control form-control-sm autocomplete" \
                    name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" \
                    autocomplete="off" data-ac-module="' + target_module + '" data-ac-field="' + target_field + '"' + readonly + '>\
                </td>';
            }
            else if (field_type == "image") {
                row += '<td data-field-type="image">\
                    <div class="col-md-12 media">\
                        <div class="pull-left text-center avatar-box">\
                            <i class="fas fa-image fa-2x avatar"></i>\
                        </div>\
                        <div class="media-body text-left">\
                            <label title="Upload image file" class="btn bg-gradient-secondary btn-xs ml-3">\
                                <input type="file" accept="image/*" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" class="d-none">\
                                Change\
                            </label>\
                        </div>\
                    </div>\
                </td>';
            }
            else if (field_type == "select") {
                row += '<td data-field-type="select">\
                    <select class="custom-select custom-select-sm" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']">';

                $.each($(heads).data("options").split(","), function(index, option) {
                    option = trim(option);
                    option = option.split(":");

                    if (option.length == 2) {
                        var option_value = option[1];
                        var option_label = option[0];
                    }
                    else {
                        var option_value = option[0];
                        var option_label = option[0];
                    }

                    row += '<option value="' + option_value + '">' + option_label + '</option>';
                });

                row += '</select></td>';
            }
            else if (field_type == "checkbox") {
                row += '<td data-field-type="checkbox"' + hidden + ' class="text-center" style="vertical-align: middle;">\
                    <div class="custom-control custom-checkbox text-center">\
                        <input type="hidden" class="checkbox-value" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" value="0">\
                        <input type="checkbox" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" id="check-' + (idx - 1) + '" class="custom-control-input" ' + readonly + '>\
                        <label class="custom-control-label" for="check-' + (idx - 1) + '"></label>\
                    </div>\
                </td>';
            }
            else if (field_type == "date") {
                row += '<td data-field-type="date">\
                    <div class="input-group">\
                        <span class="input-group-prepend">\
                            <span class="input-group-text">\
                                <i class="fas fa-calendar-alt fa-sm"></i>\
                            </span>\
                        </span>\
                        <input type="text" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" class="form-control form-control-sm datepicker pl-0" autocomplete="off">\
                    </div>\
                </td>';
            }
            else if (field_type == "time") {
                row += '<td data-field-type="time">\
                    <div class="input-group">\
                        <span class="input-group-prepend">\
                            <span class="input-group-text">\
                                <i class="fas fa-clock fa-sm"></i>\
                            </span>\
                        </span>\
                        <input type="text" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" class="form-control form-control-sm timepicker pl-0" autocomplete="off">\
                    </div>\
                </td>';
            }
            else if (field_type == "datetime") {
                row += '<td data-field-type="datetime">\
                    <div class="input-group">\
                        <span class="input-group-prepend">\
                            <span class="input-group-text">\
                                <i class="fas fa-calendar-alt fa-sm"></i>\
                            </span>\
                        </span>\
                        <input type="text" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" class="form-control form-control-sm datetimepicker pl-0" autocomplete="off">\
                    </div>\
                </td>';
            }
            else if (field_type == "text") {
                if (target_module && target_field) {
                    row += '<td data-field-type="' + field_type + '"' + hidden + '>\
                        <input type="text" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" \
                        class="form-control form-control-sm" data-ac-module="' + target_module + '" data-ac-field="' + target_field + '" autocomplete="off"' + readonly + '>\
                    </td>';
                }
                else {
                    row += '<td data-field-type="' + field_type + '"' + hidden + '>\
                        <input type="text" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" \
                        class="form-control form-control-sm" autocomplete="off"' + readonly + '>\
                    </td>';
                }
            }
            else if (field_type == "textarea") {
                row += '<td data-field-type="textarea"' + hidden + '>\
                    <textarea rows="5" cols="8" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" \
                    class="form-control form-control-sm" autocomplete="off"></textarea>\
                </td>';
            }
            else if (field_type == "blank") {
                row += '<td data-field-type="blank" data-field-name="' + field_name + '"' + hidden + '></td>';
            }
        }
    });

    row += '</tr>';

    $(tbody).append(row);
    maintainIdx(tbody);
    enableAutocomplete();
    setPickersInTable(table_name, table, field_types);
}

function maintainIdx(tbody) {
    var idx = 1;

    $.each($(tbody).find("tr"), function(index, row) {
        if ($(row).is(":visible")) {
            $(row).attr("idx", idx);
            $(row).find("td:first").html(idx);
            idx++;
        }
    });
}

function showEmptyRow(table) {
    var colspan = $(table).find("thead > tr > th").length;
    var empty_row = '<tr class="odd">\
        <td valign="middle" align="center" colspan="' + colspan + '">Empty</td>\
    </tr>';

    $(table).find("tbody").append(empty_row);
}

// add multiple rows for table at the time of loading
function addNewRows(table_name, records) {
    var table = $('table[data-table="' + table_name + '"]');
    var thead = $(table).find("thead");
    var tbody = $(table).find("tbody");
    var field_types = [];
    var rows = '';

    // remove empty row
    if ($(tbody).find("tr").hasClass("odd")) {
        $(tbody).empty();
    }

    var tbody_len = $(tbody).find("tr").length;

    $.each(records, function(idx, value) {
        if (tbody_len) {
            idx = tbody_len;
        }

        rows += '<tr class="table_record">';

        $.each($(thead).find("tr > th"), function(index, heads) {
            var field_type = $(heads).data("field-type");
            var field_name = $(heads).data("field-name");
            var target_module = $(heads).data("ac-module");
            var target_field = $(heads).data("ac-field");
            var readonly = ($(heads).data("readonly") == "yes") ? "readonly" : "";
            var hidden = ($(heads).data("hidden") == "yes") ? "style='display: none;'" : "";
            field_types.push(field_type);

            // get value for the field
            if (value[field_name] && typeof value[field_name] === 'string' && (value[field_name].isDate() || value[field_name].isDateTime())) {
                if (value[field_name].split(" ").length > 1) {
                    field_value = moment.utc(value[field_name]).local().format('DD-MM-YYYY hh:mm A');
                }
                else {
                    field_value = moment.utc(value[field_name]).local().format('DD-MM-YYYY');
                }
            }
            else if (value[field_name] && typeof value[field_name] === 'string' && value[field_name].isTime()) {
                field_value = moment.utc(value[field_name], ["HH:mm:ss"]).local().format('HH:mm');
            }
            else {
                field_value = value[field_name] || '';
            }

            // set default table values
            if ($(heads).hasClass('sr-no')) {
                rows += '<td class="text-center" style="vertical-align: middle;">' + (idx + 1) + '</td>';
            }
            else if ($(heads).hasClass('remove')) {
                rows += '<td class="text-center" data-idx="' + (idx + 1) + '" style="vertical-align: middle;">\
                    <button type="button" class="btn btn-danger btn-xs remove-row">\
                        <i class="fas fa-times p-1"></i>\
                    </button>\
                </td>';
            }
            else if ($(heads).hasClass('action')) {
                // while showing data
                if (value["id"]) {
                    var action = "none";
                }
                // while copying data
                else {
                    var action = "create";
                }

                rows += '<td class="action" style="display: none;">\
                    <input type="text" class="form-control form-control-sm" name="' + table_name + '[' + idx + '][action]" value="' + action + '">\
                </td>';
            }
            else if ($(heads).hasClass("row-id")) {
                rows += '<td class="row-id" style="display: none;">\
                    <input type="text" class="form-control form-control-sm" name="' + table_name + '[' + idx + '][id]" value="' + value["id"] + '">\
                </td>';
            }
            // set field value
            else {
                if (field_type == "link") {
                    rows += '<td data-field-type="link">\
                        <input type="text" class="form-control form-control-sm autocomplete" \
                        name="' + table_name + '[' + idx + '][' + field_name + ']" \
                        autocomplete="off" data-ac-module="' + target_module + '" data-ac-field="' + target_field + '"' + readonly + ' value="' + field_value + '">\
                    </td>';
                }
                else if (field_type == "image") {
                    rows += '<td data-field-type="image">\
                        <div class="col-md-12 media">\
                            <div class="pull-left text-center avatar-box">';

                    if (value[field_name]) {
                        rows += '<img src="' + getImage(value[field_name], "100", "100") + '" class="fancyimg" data-big="' + getImage(value[field_name]) + '" alt="Image">';
                    }
                    else {
                        rows += '<i class="fas fa-image fa-2x avatar"></i>';
                    }

                    rows += '</div>\
                            <div class="media-body text-left">\
                                <label title="Upload image file" class="btn bg-gradient-secondary btn-xs ml-3">\
                                    <input type="file" accept="image/*" name="' + table_name + '[' + idx + '][' + field_name + ']" class="d-none">\
                                    Change\
                                </label>\
                            </div>\
                        </div>\
                    </td>';
                }
                else if (field_type == "select") {
                    rows += '<td data-field-type="select">\
                        <select class="custom-select custom-select-sm" name="' + table_name + '[' + idx + '][' + field_name + ']">';

                    $.each($(heads).data("options").split(","), function(index, option) {
                        option = trim(option);
                        option = option.split(":");

                        if (option.length == 2) {
                            var option_value = option[1];
                            var option_label = option[0];
                        }
                        else {
                            var option_value = option[0];
                            var option_label = option[0];
                        }

                        if (option_value == value[field_name]) {
                            rows += '<option value="' + option_value + '" default selected>' + option_label + '</option>';
                        }
                        else {
                            rows += '<option value="' + option_value + '">' + option_label + '</option>';
                        }
                    });

                    rows += '</select></td>';
                }
                else if (field_type == "checkbox") {
                    rows += '<td data-field-type="checkbox"' + hidden + ' class="text-center" style="vertical-align: middle;">\
                        <div class="custom-control custom-checkbox text-center">\
                            <input type="hidden" class="checkbox-value" name="' + table_name + '[' + idx + '][' + field_name + ']" ' + readonly + ' value="' + (parseInt(field_value) ? 1 : 0) + '">\
                            <input type="checkbox" name="' + table_name + '[' + idx + '][' + field_name + ']" id="check-' + idx + '" class="custom-control-input" ' + readonly + (parseInt(field_value) ? " checked" : "") + '>\
                            <label class="custom-control-label" for="check-' + idx + '"></label>\
                        </div>\
                    </td>';
                }
                else if (field_type == "date") {
                    rows += '<td data-field-type="date">\
                        <div class="input-group">\
                            <span class="input-group-prepend">\
                                <span class="input-group-text">\
                                    <i class="fas fa-calendar-alt fa-sm"></i>\
                                </span>\
                            </span>\
                            <input type="text" name="' + table_name + '[' + idx + '][' + field_name + ']" class="form-control form-control-sm datepicker pl-0" autocomplete="off" value="' + field_value + '">\
                        </div>\
                    </td>';
                }
                else if (field_type == "time") {
                    rows += '<td data-field-type="time">\
                        <div class="input-group">\
                            <span class="input-group-prepend">\
                                <span class="input-group-text">\
                                    <i class="fas fa-clock fa-sm"></i>\
                                </span>\
                            </span>\
                            <input type="text" name="' + table_name + '[' + idx + '][' + field_name + ']" class="form-control form-control-sm timepicker pl-0" autocomplete="off" value="' + field_value + '">\
                        </div>\
                    </td>';
                }
                else if (field_type == "datetime") {
                    rows += '<td data-field-type="datetime">\
                        <div class="input-group">\
                            <span class="input-group-prepend">\
                                <span class="input-group-text">\
                                    <i class="fas fa-calendar-alt fa-sm"></i>\
                                </span>\
                            </span>\
                            <input type="text" name="' + table_name + '[' + idx + '][' + field_name + ']" class="form-control form-control-sm datetimepicker pl-0" autocomplete="off" value="' + field_value + '">\
                        </div>\
                    </td>';
                }
                else if (field_type == "text") {
                    if (target_module && target_field) {
                        rows += '<td data-field-type="' + field_type + '"' + hidden + '>\
                            <input type="text" name="' + table_name + '[' + idx + '][' + field_name + ']" \
                            class="form-control form-control-sm" data-ac-module="' + target_module + '" data-ac-field="' + target_field + '" autocomplete="off"' + readonly + ' value="' + field_value + '">\
                        </td>';
                    }
                    else {
                        rows += '<td data-field-type="' + field_type + '"' + hidden + '>\
                            <input type="text" name="' + table_name + '[' + idx + '][' + field_name + ']" \
                            class="form-control form-control-sm" autocomplete="off"' + readonly + ' value="' + field_value + '">\
                        </td>';
                    }
                }
                else if (field_type == "textarea") {
                    rows += '<td data-field-type="textarea"' + hidden + '>\
                        <textarea rows="5" cols="8" name="' + table_name + '[' + idx + '][' + field_name + ']" \
                        class="form-control" autocomplete="off">' + field_value + '</textarea>\
                    </td>';
                }
                else if (field_type == "blank") {
                    rows += '<td data-field-type="blank" data-field-name="' + field_name + '"' + hidden + '>' + field_value + '</td>';
                }
            }
        });

        rows += '</tr>';

        if (tbody_len) {
            tbody_len++;
        }
    });

    $(tbody).append(rows);
    enableAutocomplete();
    setPickersInTable(table_name, table, field_types);
    enableFancyBox();
}

// set datepicker, datetimepicker in child table
function setPickersInTable(table_name, table, field_types) {
    // set date field inside table elements
    if (field_types.contains("date")) {
        $.each($("table > tbody > tr").find(".datepicker"), function(idx, element) {
            $(element).datetimepicker({
                icons: {
                    time: 'fas fa-clock',
                    date: 'fas fa-calendar-alt',
                    up: 'fas fa-chevron-up',
                    down: 'fas fa-chevron-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-crosshairs',
                    clear: 'fas fa-trash',
                    close: 'fas fa-times'
                },
                format: 'DD-MM-YYYY',
                allowInputToggle: true
            }).on('dp.change', function(ev) {
                if (typeof origin.data[table_name] !== "undefined") {
                    var doc_records = origin.data[table_name].length;
                }
                else {
                    var doc_records = 0;
                }

                var tab_records = $(table).find("tbody > tr").length;

                if ($.trim($('body').find('[name="id"]').val()) && doc_records == tab_records) {
                    $(element).closest("tr").find("td.action > input").val("update");
                }

                if (typeof changeDoc === "function") {
                    changeDoc();
                }
            });
        });
    }

    // set time field inside table elements
    if (field_types.contains("time")) {
        $.each($("table > tbody > tr").find(".timepicker"), function(idx, element) {
            $(element).datetimepicker({
                icons: {
                    up: 'fas fa-chevron-up',
                    down: 'fas fa-chevron-down',
                },
                format: 'hh:mm A',
                allowInputToggle: true
            }).on('dp.change', function(ev) {
                if (typeof origin.data[table_name] !== "undefined") {
                    var doc_records = origin.data[table_name].length;
                }
                else {
                    var doc_records = 0;
                }

                var tab_records = $(table).find("tbody > tr").length;

                if ($.trim($('body').find('[name="id"]').val()) && doc_records == tab_records) {
                    $(element).closest("tr").find("td.action > input").val("update");
                }

                if (typeof changeDoc === "function") {
                    changeDoc();
                }
            });
        });
    }

    // set datetime field inside table elements
    if (field_types.contains("datetime")) {
        $.each($("table > tbody > tr").find(".datetimepicker"), function(idx, element) {
            $(element).datetimepicker({
                icons: {
                    time: 'fas fa-clock',
                    date: 'fas fa-calendar-alt',
                    up: 'fas fa-chevron-up',
                    down: 'fas fa-chevron-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-crosshairs',
                    clear: 'fas fa-trash',
                    close: 'fas fa-times'
                },
                format: 'DD-MM-YYYY hh:mm A',
                allowInputToggle: true,
            }).on('dp.change', function(ev) {
                if (typeof origin.data[table_name] !== "undefined") {
                    var doc_records = origin.data[table_name].length;
                }
                else {
                    var doc_records = 0;
                }

                var tab_records = $(table).find("tbody > tr").length;

                if ($.trim($('body').find('[name="id"]').val()) && doc_records == tab_records) {
                    $(element).closest("tr").find("td.action > input").val("update");
                }

                if (typeof changeDoc === "function") {
                    changeDoc();
                }
            });
        });
    }
}