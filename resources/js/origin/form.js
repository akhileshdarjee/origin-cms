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
    $("body").on("click", "#delete", function() {
        var current_url = app_route;
        var link_field_value = current_url.split('/').pop();
        var delete_path = current_url.replace("/" + link_field_value, "/delete/" + link_field_value);

        var footer = '<span class="pull-right">\
            <button type="button" class="btn btn-sm" data-dismiss="modal">No</button>\
            <a class="btn btn-danger btn-sm" href="' + delete_path + '" id="yes" name="yes">\
                Delete\
            </a>\
        </span>';

        msgbox("Sure you want to delete this record permanently?", footer);
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

        $.each(mandatory_fields, function(index, field) {
            if (!trim($(field).val())) {
                e.preventDefault();
                validated = false;
                var field_name = $(field).attr("name");

                if ($(field).closest('.table_record')) {
                    if (field_name.match(/\[(.*?)\]/g)) {
                        field_name = field_name.match(/\[(.*?)\]/g).pop().replace('[', '').replace(']', '');
                    }
                }

                notify("Please Enter " + field_name.replace("_", " ").toProperCase(), "error");
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

    $.each($form_elements, function(index, element) {
        if ($(this).data("mandatory") == "yes") {
            mandatory_fields.push($(element)[0]);
        }
    });

    return mandatory_fields;
}

// show error label and input to all mandatory fields
function highlightMandatoryFields(mandatory_fields) {
    if (!mandatory_fields) {
        mandatory_fields = getMandatoryFields();
    }

    $.each(mandatory_fields, function(index, field) {
        if ($.trim($(this).val()) == "") {
            // if not child table field
            if (!$(field).closest('.table_record')) {
                $(field).closest(".form-group").addClass("has-error");
            }

            $(field).addClass("error");
        }
    });
}

// remove highlight if data is entered on mandatory fields
function removeMandatoryHighlight(mandatory_fields) {
    if (!mandatory_fields) {
        mandatory_fields = getMandatoryFields();
    }

    $.each(mandatory_fields, function() {
        $parent_div = $(this).closest(".form-group");

        if ($.trim($(this).val())) {
            // if not child table field
            if (!$(this).closest('.table_record')) {
                $($parent_div).removeClass("has-error");
            }

            $(this).removeClass("error");
        }
        else {
            // if not child table field
            if (!$(this).closest('.table_record')) {
                $($parent_div).addClass("has-error");
            }

            $(this).addClass("error");
        }
    });
}

// make all fields readable
function makeFieldsReadable() {
    $form_elements = $("form").find("input, select, textarea");

    $.each($form_elements, function(index, element) {
        var ele_type = $(element).attr("type");

        if (!["hidden", "file"].contains(ele_type)) {
            var new_control = '';

            if (ele_type == "checkbox") {
                if ($(element).is(":checked")) {
                    new_control = '<i class="fa fa-check-square-o"></i>';
                }
                else {
                    new_control = '<i class="fa fa-square-o"></i>';
                }
            }
            else {
                var ele_val = $(element).val();

                if ($(element).attr("name") == "active") {
                    ele_val = parseInt(ele_val) ? "Yes" : "No";
                }

                ele_val = ele_val ? ele_val : '';
                new_control = '<p class="form-control-static origin-static">' + ele_val + '</p>';
            }

            $(new_control).insertBefore($(element));
        }

        if ($(element).attr("type") == "file") {
            $(element).closest('.btn').remove();
        }

        $(element).remove();
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

    $("body").find("#save_form").removeClass("disabled");
    $("body").find("#save_form").prop("disabled", false);
    $("body").find("#form-stats > i").removeClass("text-success").addClass("text-warning");
    $("body").find("#form-status").html('Not Saved');
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
                        if (typeof value === 'string' && (value.isDate() || value.isDateTime())) {
                            $(form_field).attr("data-field-value", value);

                            if (value.isDateTime()) {
                                value = moment(value).format('DD-MM-YYYY hh:mm A');
                            }
                            else {
                                value = moment(value).format('DD-MM-YYYY');
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

        // set button loading text if given
        if(typeof button_config['loading_text'] != 'undefined' && button_config['loading_text']) {
            element.setAttribute("data-loading-text", button_config['loading_text']);
        }
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
    var form_static = '<div class="col-md-3"><span class="control-label">' + label + '</span>: ' + value + '</div>';

    if ($('body').find('.form-statics').length) {
        var form_static_container = $('body').find('.form-statics');
        $(form_static_container).find('.static-list').append(form_static);
    }
    else {
        var form_static_container = '<div class="box-body form-statics"><div class="row static-list">' + form_static + '</div></div>';
        $('body').find('.form-section').first().prepend(form_static_container);
    }
}