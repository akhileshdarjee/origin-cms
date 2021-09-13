var mandatory_fields = getMandatoryFields();

$(document).ready(function() {
    var disable_fields = false;

    // if form has been changed then enable form save button
    $('form#' + origin.slug).on('change input', 'input, select, textarea', function() {
        changeDoc();
    });

    // show images files
    $('form#' + origin.slug).on("change", "input[type='file']", function() {
        if ($(this).val()) {
            showImagePreview(this);
        }
    });

    // shows msgbox to delete the record permanently
    $("body").on("click", "#delete", function(e) {
        e.preventDefault();

        var current_url = app_route;
        var link_field_value = current_url.split('/').pop();
        var delete_path = current_url.replace("/" + link_field_value, "/delete/" + link_field_value);
        var title = __('Delete');
        var modal_body = '<p>' + __('Sure you want to delete this record permanently') + '?</p>';

        var modal_footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">' + __("No") + '</button>\
            <a class="btn bg-gradient-danger btn-sm" href="' + delete_path + '">\
                ' + __("Delete") + '\
            </a>';

        msgbox(modal_body, modal_footer, title);
    });

    // bind save and reset button to form
    $("body").on('click', "#save_form", function() {
        $("body").find("#" + origin.slug).submit();
    });

    setDocData();
    initializeMandatoryFields();
    enableAutocomplete();

    var form_id = $('body').find('[name="id"]').val();

    if (form_id) {
        if (!origin.permissions.update) {
            disable_fields = true;
        }
    } else {
        if (!origin.permissions.create) {
            disable_fields = true;
        }
    }

    if (origin.module == "Settings") {
        disable_fields = false;
    }

    if (disable_fields) {
        makeFieldsReadable();
    }

    // validate forms for mandatory fields
    $('form#' + origin.slug).submit(function(e) {
        var validated = true;

        $.each(mandatory_fields, function(idx, field) {
            if (!$.trim($(field).val())) {
                e.preventDefault();
                validated = false;
                var field_label = $.trim($(field).closest('.form-group').find('.control-label').html());

                if ($(field).closest('.table_record').length) {
                    var table_record = $(field).closest('.table_record');
                    var cell_index = $(table_record).index();
                    var field_label = $(table_record).closest('.child-table').find("thead > tr > th").eq(cell_index).html();
                }

                field_label = field_label.replace(' <span class="text-danger">*</span>', '');
                notify(__('Please enter') + " " + field_label, "error");
                $(field).focus();

                return false;
            }
        });

        if (validated) {
            // checkbox toggle value
            $.each($('form#' + origin.slug).find("input[type='checkbox']"), function(idx, checkbox) {
                if (this.checked) {
                    $(this).val("1");
                    $(this).closest('.checkbox-value').prop("disabled", true);
                }
                else {
                    $(this).val("0");
                }
            });

            $("body").find(".data-loader-full").show();
        }
    });
});

// calls required functions for changing doc state
function changeDoc() {
    origin.changed = true;
    initializeMandatoryFields();
    removeMandatoryHighlight(mandatory_fields);
    enableSaveButton();
}

// get all mandatory fields and highlight
function initializeMandatoryFields() {
    mandatory_fields = getMandatoryFields();
    highlightMandatoryFields(mandatory_fields);
}

// fetch all mandatory fields inside a form
function getMandatoryFields() {
    var mandatory_fields = [];
    $form_elements = $("form").find("input, select, textarea");

    $.each($form_elements, function(idx, field) {
        if ($(field).data("mandatory") == "yes") {
            mandatory_fields.push($(field)[0]);
        }
    });

    return mandatory_fields;
}

// show error label and input to all mandatory fields
function highlightMandatoryFields(mandatory_fields) {
    if (!mandatory_fields) {
        mandatory_fields = getMandatoryFields();
    }

    $.each(mandatory_fields, function(idx, field) {
        if (!$.trim($(field).val()) && $.trim($(field).val()) != '0') {
            // if not child table field
            if (!$(field).closest('.table_record').length) {
                $(field).closest(".form-group").addClass("is-invalid");
            }

            $(field).addClass("is-invalid");
        }
    });
}

// remove highlight if data is entered on mandatory fields
function removeMandatoryHighlight(mandatory_fields) {
    if (!mandatory_fields) {
        mandatory_fields = getMandatoryFields();
    }

    $.each(mandatory_fields, function(idx, field) {
        $parent_div = $(field).closest(".form-group");

        if ($.trim($(field).val())) {
            // if not child table field
            if (!$(field).closest('.table_record').length) {
                $($parent_div).removeClass("is-invalid");
            }

            $(field).removeClass("is-invalid");
        }
        else {
            // if not child table field
            if (!$(field).closest('.table_record').length) {
                $($parent_div).addClass("is-invalid");
            }

            $(field).addClass("is-invalid");
        }
    });
}

// make all fields readable
function makeFieldsReadable() {
    $form_elements = $("form#" + origin.slug).find("input, select, textarea");

    $.each($form_elements, function(index, element) {
        var ele_type = $(element).attr("type");
        var ele_name = $(element).attr("name");
        var is_input_group = false;

        if (!["hidden", "file"].contains(ele_type)) {
            var new_control = '';

            if (ele_type == "checkbox") {
                if ($(element).is(":checked")) {
                    new_control = '<i class="far fa-check-square fa-lg"></i>';
                }
                else {
                    new_control = '<i class="far fa-square fa-lg"></i>';
                }
            }
            else {
                var ele_val = $(element).val();

                if ($(element).attr("name") == "active") {
                    ele_val = parseInt(ele_val) ? "Yes" : "No";
                }

                ele_val = ele_val ? ele_val : '';

                if ($(element).closest('.form-group').find('.input-group').length) {
                    is_input_group = true;
                    var input_group = $(element).closest('.form-group').find('.input-group');

                    if ($(input_group).find('.input-group-append').length) {
                        ele_val = '<span class="mr-2">' + ele_val + '</span>' + $.trim($(input_group).find('.input-group-text').html());
                    }
                    else {
                        ele_val = $.trim($(input_group).find('.input-group-text').html()) + '<span class="ml-2">' + ele_val + '</span>';
                    }

                    new_control = '<p class="form-control-static origin-static" data-name="' + ele_name + '">' + ele_val + '</p>';
                }
                else {
                    new_control = '<p class="form-control-static origin-static" data-name="' + ele_name + '">' + ele_val + '</p>';
                }
            }

            if (is_input_group) {
                $(new_control).insertBefore($(element).closest('.form-group').find('.input-group'));
            }
            else {
                if (ele_type == "checkbox") {
                    $(new_control).insertBefore($(element).closest('.custom-checkbox'));
                }
                else {
                    $(new_control).insertBefore($(element));
                }
            }
        }

        if ($(element).attr("type") == "file") {
            $(element).closest('.btn').remove();
        }

        if (is_input_group) {
            $(element).closest('.form-group').find('.input-group').remove();
        }
        else {
            if (ele_type == "checkbox") {
                $(element).closest('.custom-checkbox').remove();
            }
            else {
                $(element).remove();
            }
        }
    });

    // hide remove row & add new row buttons from child tables
    $.each($('table'), function(idx, tbl) {
        $(tbl).find('th.remove').remove();
        $(tbl).find('.remove-row').closest('td').remove();
        $(tbl).find('.new-row').closest('tr').remove();
    });
}

// enable save button
function enableSaveButton() {
    form_changed = true;

    $('body').find('#save_form').removeClass("disabled");
    $('body').find('#save_form').prop("disabled", false);
    $('body').find('.status-indicator').removeClass('indicator-success');
    $('body').find('.status-indicator').addClass('indicator-orange');
    $('body').find('.status-indicator').html(__('Not Saved'));
}

// show selected image file preview
function showImagePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var avatar_box = '<img src="' + e.target.result + '">';
            $(input).closest('.media').find('.avatar-box').empty().append(avatar_box);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

// set data to form
function setDocData() {
    if (typeof origin.data != 'undefined' && origin.data) {
        $.each(origin.data, function(table_name, table_data) {
            $.each(table_data, function(field_name, value) {
                if (typeof value === 'string' || typeof value === 'number') {
                    var form_field = $('form#' + origin.slug).find('[name="' + field_name + '"]');

                    if ($(form_field).length && $(form_field).attr("type") != "file") {
                        if (typeof value === 'string' && (value.isDate() || value.isDateTime() || value.isTime())) {
                            $(form_field).attr("data-field-value", value);

                            if (value.isDate()) {
                                value = moment.utc(value).tz(origin.time_zone).format('DD-MM-YYYY');
                            }
                            else if (value.isDateTime()) {
                                value = moment.utc(value).tz(origin.time_zone).format('DD-MM-YYYY hh:mm A');
                            }
                            else {
                                value = moment.utc('0001-01-01 ' + column_value).tz(origin.time_zone).format('hh:mm A');
                            }
                        }

                        $(form_field).val(value);
                    }
                }
                else if (typeof value === 'object' && value) {
                    addNewRows(table_name, table_data);
                    return false;
                }
            });
        });

        // set text editor value if found
        $('form#' + origin.slug).find(".text-editor, .text-editor-advanced").each(function(idx, field) {
            $(field).trumbowyg('html', $(field).val());
        });

        if (!origin.permissions.update) {
            $("body").find('.text-editor, .text-editor-advanced').trumbowyg('disable');
            $("body").find('.text-editor, .text-editor-advanced').remove();
        }
    }
}

// create custom button
window.origin.make = {
    button: function (button_config) {
        var button_text = button_config['text'];
        var button_name = button_config['name'];

        // get button class from given config or assign default classs
        if(typeof button_config['class'] != 'undefined' && button_config['class']) {
            var button_class = "btn " + button_config['class'];
        }
        else {
            var button_class = "btn btn-primary";
        }

        // create button element with it's given config
        var element = document.createElement("button");
        element.setAttribute("type", "button");
        element.setAttribute("name", button_name);
        element.setAttribute("id", button_name);
        element.setAttribute("class", button_class);
        element.appendChild(document.createTextNode(button_text));

        // append button on form title section
        $("body").find(".ibox-tools").prepend(element);

        // bind on click method to the dynamically created button if passed in button config
        if (typeof button_config['on_click'] != 'undefined' && button_config['on_click']) {
            $("#" + button_name).on("click", function() {
                button_config['on_click']();
            });
        }
    }
};

function addFormStatic(label, value) {
    var form_static = '<div class="col-md-3 form-info-box"><span class="control-label">' + label + ':</span> ' + value + '</div>';

    if ($('body').find('.form-statics').length) {
        var form_static_container = $('body').find('.form-statics');
        $(form_static_container).find('.static-list').append(form_static);
    }
    else {
        var form_static_container = '<div class="card form-section elevation-2">\
            <div class="card-header form-statics">\
                <div class="row static-list">' + form_static + '</div>\
            </div>\
        </div>';

        $('body').find('.form-section').first().before(form_static_container);
    }
}