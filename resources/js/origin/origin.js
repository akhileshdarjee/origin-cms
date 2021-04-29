// convert string to proper case
String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};

// convert string to snake case
String.prototype.toSnakeCase = function () {
    return this.replace(/(.)([A-Z])/g, "$1_$2").toLowerCase();
};

// check if string is a valid date
String.prototype.isDate = function () {
    var dateFormat;

    if (toString.call(this) === '[object Date]') {
        return true;
    }
    if (typeof this.replace === 'function') {
        this.replace(/^\s+|\s+$/gm, '');
    }

    dateFormat = /(^\d{1,4}[\.|\\/|-]\d{1,2}[\.|\\/|-]\d{1,4})(\s*(?:0?[1-9]:[0-5]|1(?=[012])\d:[0-5])\d\s*[ap]m)?$/;

    if (dateFormat.test(this)) {
        return !!new Date(this).getTime();
    }

    return false;
};

// check if string is a time
String.prototype.isTime = function () {
    var isValid = /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])(:[0-5][0-9])?$/.test(this);
    return isValid;
};

// check if string is a time
String.prototype.isDateTime = function () {
    var date = this.split(" ");

    if (date[0].isDate() && date[1].isTime()) {
        return true;
    }

    return false;
};

// check if the string is a url/link
String.prototype.isURL = function() {
    var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    return pattern.test(this);
}

// create slug from string
String.prototype.slugify = function() {
    str = this.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    var from = "ÁÄÂÀÃÅČÇĆĎÉĚËÈÊẼĔȆÍÌÎÏŇÑÓÖÒÔÕØŘŔŠŤÚŮÜÙÛÝŸŽáäâàãåčçćďéěëèêẽĕȇíìîïňñóöòôõøðřŕšťúůüùûýÿžþÞĐđßÆa·/_,:;";
    var to   = "aaaaaacccdeeeeeeeeiiiinnoooooorrstuuuuuyyzaaaaaacccdeeeeeeeeiiiinnooooooorrstuuuuuyyzbBDdBAa------";

    for (var i=0, l=from.length ; i<l ; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/&/g, '-and-') // replace '&' with 'and'
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-') // collapse dashes
        .replace(/[^\w\-]+/g, '') // remove all non-word chars
        .replace(/\-\-+/g, '-') // replace multiple '-' with single '-'
        .replace(/^-+/, '') // Trim - from start of text
        .replace(/-+$/, ''); // Trim - from end of text

    return str;
}

// Prototyping for getting month long name and short name
Date.prototype.getMonthName = function(lang) {
    lang = lang && (lang in Date.locale) ? lang : 'en';
    return Date.locale[lang].month_names[this.getMonth()];
};

Date.prototype.getMonthNameShort = function(lang) {
    lang = lang && (lang in Date.locale) ? lang : 'en';
    return Date.locale[lang].month_names_short[this.getMonth()];
};

Date.locale = {
    en: {
        month_names: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        month_names_short: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    }
};

// check if array contains element
Array.prototype.contains = function(obj) {
    var i = this.length;
    while (i--) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
}

// get random element from array
Array.prototype.random = function () {
    return this[Math.floor((Math.random()*this.length))];
}

// get object from localstorage
Storage.prototype.getObject = function(key) {
    var value = this.getItem(key);
    return value && JSON.parse(value);
}

// set object in localstorage
Storage.prototype.setObject = function(key, value) {
    this.setItem(key, JSON.stringify(value));
}

// set common global variables
var app_route = window.location.href;
var base_url = $('body').attr('data-base-url');

var isMobile = false;
// device detection
if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
    isMobile = true;
}

// Setup ajax for making csrf token used by laravel
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

$(function() {
    $(window).scroll(stickyRelocate);
    stickyRelocate();
});

moment.locale(origin.locale);

$(document).ready(function() {
    // set body data url
    $("body").attr("data-url", app_route);

    // enable tooltips
    $("body").tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover',
        delay: { show: 500 }
    });

    // back to top
    var amountScrolled = 300;

    $(window).scroll(function() {
        if ($(window).scrollTop() > amountScrolled) {
            $('a.back-to-top').fadeIn('slow');
        }
        else {
            $('a.back-to-top').fadeOut('slow');
        }
    });

    $('body').on('click', '.change-theme', function(e) {
        e.stopPropagation();

        if ($(e.target).is("a")) {
            e.preventDefault();
            var dark_mode = $('body').find('[name="toggle_app_theme"]');
            var theme = 'light';

            if ($(dark_mode).is(":checked")) {
                $(dark_mode).prop('checked', false);
            }
            else {
                $(dark_mode).prop('checked', true);
                theme = 'dark';
            }

            changeTheme(theme);
        }
    });

    $('body').on('change', '[name="toggle_app_theme"]', function(e) {
        var theme = 'light';

        if ($(this).is(":checked")) {
            theme = 'dark';
        }

        changeTheme(theme);
    });

    $('a.back-to-top').click(function() {
        $('body, html').animate({
            scrollTop: 0
        }, 400);

        return false;
    });

    $.each($(".app-nav"), function(index, app_nav) {
        if ($(app_nav).find('a').attr('href') == window.location.href) {
            $(app_nav).addClass('active');
        }
        else {
            $(app_nav).removeClass('active');
        }
    });

    enableAutocomplete();
    enableDatePicker();
    enableTimePicker();
    enableDateTimePicker();
    enableTextEditor();
    enableAdvancedTextEditor();
    enableFancyBox();

    // allow only numbers to text box
    $('body').on('input change', '.numbers-only', function() {
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });
});

function applyTheme(theme) {
    if (theme == 'dark') {
        $('body').find('.navbar').removeClass('navbar-light navbar-white');
        $('body').find('.navbar').addClass('navbar-dark');
        $('body').addClass('dark-mode');

        if ($('body').find('.module-btn').length) {
            $.each($('body').find('.module-btn'), function(idx, mod_btn) {
                var default_color = $(mod_btn).data('default-color');
                var dark_color = luminance(default_color, -0.4);
                $(mod_btn).css('background-color', dark_color);
            });
        }

        if ($('body').find('.report-btn').length) {
            $.each($('body').find('.report-btn'), function(idx, rep_btn) {
                var default_color = $(rep_btn).data('default-color');
                var dark_color = luminance(default_color, -0.4);
                $(rep_btn).css('background-color', dark_color);
                $(rep_btn).css('border-color', dark_color);
            });
        }
    }
    else {
        $('body').find('.navbar').removeClass('navbar-dark');
        $('body').find('.navbar').addClass('navbar-light navbar-white');
        $('body').removeClass('dark-mode');

        if ($('body').find('.module-btn').length) {
            $.each($('body').find('.module-btn'), function(idx, mod_btn) {
                var default_color = $(mod_btn).data('default-color');
                $(mod_btn).css('background-color', default_color);
            });
        }

        if ($('body').find('.report-btn').length) {
            $.each($('body').find('.report-btn'), function(idx, rep_btn) {
                var default_color = $(rep_btn).data('default-color');
                $(rep_btn).css('background-color', default_color);
                $(rep_btn).css('border-color', default_color);
            });
        }
    }
}

function changeTheme(theme) {
    applyTheme(theme);
    var theme_toggle = $('body').find('[name="toggle_app_theme"]');

    if (theme == 'dark') {
        theme_alt = 'light';
    }
    else {
        theme_alt = 'dark';
    }

    if ($(theme_toggle).length && $(theme_toggle).data('action')) {
        var action = $(theme_toggle).data('action');

        $.ajax({
            type: 'POST',
            url: action,
            data: {'theme': theme},
            dataType: 'json',
            success: function(data) {
                if (!data['success']) {
                    applyTheme(theme_alt);
                    notify(data['msg'], "error");
                }
            },
            error: function(e) {
                applyTheme(theme_alt);

                if (typeof JSON.parse(e.responseText)['message'] !== 'undefined') {
                    var error_msg = JSON.parse(e.responseText)['message'];
                }
                else {
                    var error_msg = __('Some error occured. Please try again');
                }

                notify(error_msg, "error");
            }
        });
    }
    else {
        notify(__('Please refresh the page and try again'), "error");
    }
}

// Autocomplete
function enableAutocomplete() {
    var autocomplete = $('body').find('.autocomplete');

    $.each(autocomplete, function(index, field) {
        var data_module = $(field).data("ac-module");
        var data_field = $(field).data("ac-field");
        var data_image = $(field).data("ac-image") ? $(field).data("ac-image") : "0";
        var unique = ($(field).data("ac-unique") == "Yes") ? true : false;
        var module_fields = {};

        if (unique) {
            module_fields[data_module] = [data_field];
        }
        else {
            $.each($("body").find('[data-ac-module="' + data_module + '"]'), function(index, element) {
                if (module_fields[data_module]) {
                    module_fields[data_module].push($(element).data("ac-field"));
                }
                else {
                    module_fields[data_module] = [$(element).data("ac-field")];
                }
            });
        }

        if (data_image && data_image != "0") {
            module_fields[data_module].push(data_image);
        }

        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: base_url + '/get-auto-complete',
                    dataType: "json",
                    data: {
                        module: data_module,
                        field: data_field,
                        fetch_fields: module_fields[data_module],
                        query: request.term,
                        unique: unique,
                        image_field: data_image
                    },
                    success: function(data) {
                        if (data.length) {
                            var label_field = data_field.split("+");

                            response($.map(data, function (item) {
                                var label_value = '';

                                if (label_field.length > 1) {
                                    $.each(label_field, function(l_idx, field_name) {
                                        label_value += item[field_name] + " ";
                                    });
                                }
                                else {
                                    label_value = item[label_field[0]];
                                }

                                item['label'] = trim(label_value);
                                return item;
                            }));
                        }
                        else {
                            if (request.term) {
                                var label_value = __('No matches found');
                                var label_title = 'No matches found';
                            }
                            else {
                                var label_value = __('No Data');
                                var label_title = 'No Data';
                            }

                            response([{label: label_value, label_title: label_title, val: request.term}]);
                        }
                    },
                    error: function(e) {
                        if (typeof JSON.parse(e.responseText)['message'] !== 'undefined') {
                            var error_msg = JSON.parse(e.responseText)['message'];
                        }
                        else {
                            var error_msg = __('Some error occured. Please try again');
                        }

                        notify(error_msg, "error");
                    }
                });
            },
            minLength: 0,
            select: function(event, ui) {
                if (data_module == 'Universe') {
                    ui.item.value = '';
                    window.location = ui.item['redirect_to'];
                }

                $.each(ui.item, function(key, value) {
                    if ($(field).parent().hasClass('column-value-container')) {
                        var input_field = $(field);
                    }
                    else {
                        var input_field = $('body').find('[data-ac-field="' + key + '"][data-ac-module="' + data_module + '"]');
                    }

                    if (input_field.length > 1) {
                        // when autocomplete for same module is present in parent and child
                        if ($(field).closest('.table_record').find('[data-ac-field="' + key + '"][data-ac-module="' + data_module + '"]').length) {
                            $(field).closest('.table_record').find('[data-ac-field="' + key + '"][data-ac-module="' + data_module + '"]').val(value).trigger('change');
                        }
                        else {
                            $(input_field).val(value).trigger('change');
                        }
                    }
                    else {
                        $(input_field).val(value).trigger('change');
                    }

                    if (typeof initializeMandatoryFields === 'function') { 
                        initializeMandatoryFields(); 
                    }
                    if (typeof removeMandatoryHighlight === 'function') { 
                        removeMandatoryHighlight(); 
                    }
                });
            },
            html: true,
            open: function(event, ui) {
                if (data_module == 'Universe') {
                    var left = $(this).closest('.input-group').innerWidth() - $(this).innerWidth();
                    $(".ui-autocomplete").css({"z-index": 1050, "padding": "0px", "top": "+=2", "left": "-=" + left});
                    $(".ui-autocomplete").width($(this).closest('.form-group').innerWidth());
                }
                else {
                    $(".ui-autocomplete").css({"z-index": 1050, "padding": "0px", "top": "+=2"});
                    $(".ui-autocomplete").width($(this).innerWidth());
                }
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            if (item["label_title"] == 'No matches found' || item["label_title"] == 'No Data') {
                var list_item = '<li class="text-center autocomplete-no-data ui-state-disabled">\
                    <div class="autocomplete-no-data text-muted text-sm">' + item["label"] + '</div>\
                </li>';
            }
            else {
                if (data_image && data_image != "0") {
                    var list_item = '<li class="ui-menu-li-image">';
                }
                else {
                    var list_item = '<li>';
                }

                if (data_image && data_image != "0") {
                    if (item[data_image]) {
                        var ignore_links = ['http://', 'https://'];

                        if (ignore_links.contains(item[data_image].substring(0, 7)) || ignore_links.contains(item[data_image].substring(0, 8))) {
                            var image_url = trim(item[data_image]);
                        }
                        else {
                            var image_url = getImage(item[data_image], '36', '36');
                        }

                        list_item += '<img src="' + image_url + '" class="ui-menu-item-image img-circle" />';
                    }
                    else {
                        if (data_module == 'User') {
                            var default_icon = 'fas fa-user';
                        }
                        else {
                            var default_icon = 'fas fa-image';
                        }

                        list_item += '<div class="ui-menu-item-image">\
                            <span class="default-avatar img-circle">\
                                <i class="' + default_icon + '"></i>\
                            </span>\
                        </div>';
                    }

                    list_item += '<span class="ui-menu-item-text">' + item["label"] + '</span>';
                }
                else {
                    list_item += '<div>' + item["label"] + '</div>';
                }

                list_item += '</li>';
            }

            return $(list_item).appendTo(ul);
        };

        $(this).on('focus', function() {
            if(!$(this).val().trim()) {
                $(this).keydown(); 
            }
        });
    });
}

// enable date picker for all elements on page
function enableDatePicker() {
    $("body").find(".datepicker").datetimepicker({
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
    }).on("dp.change", function(ev) {
        if (typeof changeDoc === "function") {
            changeDoc();
        }
    });
}

// enable time picker for all elements on page
function enableTimePicker() {
    $("body").find(".timepicker").datetimepicker({
        icons: {
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
        },
        format: 'hh:mm A',
        allowInputToggle: true
    }).on("dp.change", function(ev) {
        if (typeof changeDoc === "function") {
            changeDoc();
        }
    });
}

// enable datetime picker for all elements on page
function enableDateTimePicker() {
    $("body").find(".datetimepicker").datetimepicker({
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
        allowInputToggle: true
    }).on("dp.change", function(ev) {
        if (typeof changeDoc === "function") {
            changeDoc();
        }
    });
}

// enable simple text editor for all elements on page
function enableTextEditor() {
    $("body").find('.text-editor').trumbowyg({
        btns: [
            ['viewHTML'],
            ['formatting'],
            'btnGrp-design',
            ['image'],
            'btnGrp-justify',
            ['fullscreen']
        ]
    }).on('tbwchange', function() { 
        if (typeof changeDoc === "function") {
            changeDoc();
        }
    });
}

// enable advanced text editor for all elements on page
function enableAdvancedTextEditor() {
    $("body").find('.text-editor-advanced').trumbowyg({
        btnsDef: {
            image: {
                dropdown: ['insertImage', 'upload'],
                ico: 'insertImage'
            }
        },
        btns: [
            ['viewHTML'],
            ['undo', 'redo'],
            ['formatting'],
            'btnGrp-design',
            ['link'],
            ['image'],
            'btnGrp-justify',
            'btnGrp-lists',
            ['foreColor', 'backColor'],
            ['preformatted'],
            ['table'],
            ['horizontalRule'],
            ['fullscreen']
        ],
        plugins: {
            upload: {
                serverPath: base_url + '/editor-upload',
                fileFieldName: 'image'
            }
        }
    }).on('tbwchange', function() { 
        if (typeof changeDoc === "function") {
            changeDoc();
        }
    });
}

// show bootstrap modal
function msgbox(msg, footer, title, size) {
    var modal = $('body').find('#message-box');

    $(modal).on('hidden.bs.modal', function (e) {
        $(this).find('.modal-dialog').removeClass("modal-lg modal-sm");
        $(this).find('.modal-title').html(__('Message'));
        $(this).find('.modal-body').html("");
        $(this).find('.modal-footer').html("");
        $(this).find('.modal-footer').hide();
    });

    $(modal).find('.modal-title').html(title ? title : __('Message'));
    $(modal).find('.modal-body').html(msg);

    if (size == "large") {
        $(modal).find('.modal-dialog').addClass("modal-lg");
    }
    else if (size == "small") {
        $(modal).find('.modal-dialog').addClass("modal-sm");
    }

    if (footer) {
        $(modal).find('.modal-footer').html(footer);
        $(modal).find('.modal-footer').show();
    }
    else {
        $(modal).find('.modal-footer').html("");
        $(modal).find('.modal-footer').hide();
    }

    $(modal).modal('show');
}

// toastr notification
function notify(msg, type) {
    if (type == 'warning') {
        var icon = 'fas fa-exclamation-triangle text-warning';
        var title = __('Warning');
    }
    else if (type == 'info') {
        var icon = 'fas fa-info-circle text-info';
        var title = __('Info');
    }
    else if (type == 'error') {
        var icon = 'fas fa-bug text-danger';
        var title = __('Error');
    }
    else {
        var icon = 'fas fa-check text-green';
        var title = __('Success');
    }

    $(document).Toasts('create', {
        body: msg,
        title: title,
        autohide: true,
        delay: 5000,
        icon: icon + ' fa-lg',
    });
}

// add status labels, icon for money related fields
function beautifyListView(list_view) {
    // field defaults
    var money_list = ['total_amount', 'grand_total', 'rate', 'amount', 'debit', 'credit', 'price', 'total'];
    var contact_list = ['contact_no', 'phone_no', 'phone', 'mobile', 'mobile_no'];
    var address_list = ['address', 'full_address', 'city', 'venue'];
    var email_list = ['email_id', 'email'];
    var label_list = ['active', 'verified', 'show_in_module_section', 'role'];
    var label_bg = {
        'active' : { '1' : {'value': __('Yes'), 'label': 'badge-success'}, '0' : {'value': __('No'), 'label': 'badge-danger'} }, 
        'verified' : { '1' : {'value': __('Yes'), 'label': 'badge-success'}, '0' : {'value': __('No'), 'label': 'badge-danger'} }, 
        'show_in_module_section' : { '1' : {'value': __('Yes'), 'label': 'badge-success'}, '0' : {'value': __('No'), 'label': 'badge-danger'} }, 
        'role' : { 'System Administrator' : 'badge-light', 'Administrator' : 'badge-dark', 'Guest' : 'badge-info' }, 
    }

    var list_view = list_view ? list_view : ".list-view";
    var list_headers = $(list_view).find(".list-header");
    var list_view_items = $(list_view).find(".list-view-items");

    // make list view heading
    $.each($(list_headers).find("th"), function() {
        if ($(this).attr("name")) {
            var heading_name = $(this).attr("name");
            var heading = heading_name.replace(/_/g, " ").toProperCase();

            if (heading.indexOf("Id") > -1) {
                heading = heading.replace("Id", "ID");
            }

            heading = heading.split(' ');

            $.each(heading, function(i, heading_part) {
                if (heading_part == 'Bg') {
                    heading[i] = 'Background';
                }
            });

            heading = heading.join(' ');

            if (money_list.contains(heading_name)) {
                $(this).html(__(heading) + ' (<i class="fas fa-rupee-sign"></i>)');
            }
            else {
                $(this).html(__(heading));
            }
        }
    });

    if ($(list_view_items).find(".clickable_row").length > 0) {
        $.each($(list_view_items).find(".clickable_row > td"), function() {
            if ($(this).attr("data-field-name")) {
                var column_name = $(this).attr("data-field-name");
                var column_value = trim($(this).html());

                if (trim(column_value) != "") {
                    if (money_list.contains(column_name)) {
                        $(this).html('<i class="fas fa-rupee-sign mr-1"></i> ' + column_value);
                    }
                    else if (contact_list.contains(column_name)) {
                        $(this).html('<i class="fas fa-phone-alt mr-1"></i> ' + column_value);
                    }
                    else if (address_list.contains(column_name)) {
                        $(this).html('<i class="fas fa-map-marker-alt mr-1"></i> ' + column_value);
                    }
                    else if (email_list.contains(column_name)) {
                        $(this).html('<i class="fas fa-envelope mr-1"></i> ' + column_value);
                    }
                    else if (label_list.contains(column_name)) {
                        if (typeof label_bg[column_name][column_value] === "object") {
                            $(this).html('<span class="badge ' + label_bg[column_name][column_value]["label"] + '">' + __(label_bg[column_name][column_value]["value"]) + '</span>');
                        }
                        else {
                            $(this).html('<span class="badge ' + label_bg[column_name][column_value] + '">' + __(column_value) + '</span>');
                        }
                    }
                    else if (column_value.isDate()) {
                        $(this).html('<i class="fas fa-calendar-alt mr-1"></i> ' + moment.utc(column_value).tz(origin.time_zone).format('DD-MM-YYYY'));
                    }
                    else if (column_value.isDateTime()) {
                        $(this).html('<i class="fas fa-calendar-alt mr-1"></i> ' + moment.utc(column_value).tz(origin.time_zone).format('DD-MM-YYYY hh:mm A'));
                    }
                    else if (column_value.isTime()) {
                        $(this).html('<i class="fas fa-clock mr-1"></i> ' + moment.utc('0001-01-01 ' + column_value).tz(origin.time_zone).format('hh:mm A'));
                    }
                    else if (isHexColor(column_value)) {
                        $(this).html('<span style="color: ' + column_value + ';"> ' + column_value + '</span>');
                    }
                }
            }
        });
    }
}

// setup fancybox to zoom image on click
function enableFancyBox() {
    $("body").on("click", "img.fancyimg", function() {
        var img_src = $(this).attr("data-big");
        var img_src = img_src ? img_src : $(this).attr("src");

        $("body").find("#fancybox").show();
        $("body").find("#fancybox-img").attr("src", img_src);

        if ($(this).attr("alt")) {
            $("body").find("#fancybox-caption").html($(this).attr("alt"));
        }
    });

    $("body").on("click", ".fancybox-close", function() {
        $("body").find("#fancybox").hide();
    });
}

// create pagination html
function makePagination(data) {
    var first_enabled = true;
    var last_enabled = true;
    var first = data['first_page_url'];
    var prev = data['prev_page_url'];
    var next = data['next_page_url'];
    var last = data['last_page_url'];

    var pagination = '<ul class="pagination pagination-sm origin-pagination float-right">';

    if (data['current_page'] == 1) {
        first_enabled = false;
    }

    if (data['current_page'] == data['last_page']) {
        last_enabled = false;
    }

    pagination += '<li class="page-item first' + (first_enabled ? "" : " disabled") + '">\
        <a class="page-link" href="' + (first_enabled ? first : "#") + '" data-dt-idx="0" tabindex="0">\
            <span class="d-none d-sm-none d-md-block">' + __("First") + '</span>\
            <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-angle-double-left"></i></span>\
        </a>\
    </li>\
    <li class="page-item previous' + (prev ? "" : " disabled") + '">\
        <a class="page-link" href="' + (prev ? prev : "#") + '" data-dt-idx="1" tabindex="0">\
            <span class="d-none d-sm-none d-md-block">' + __("Previous") + '</span>\
            <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-angle-left"></i></span>\
        </a>\
    </li>';

    pagination += '<li class="page-item next' + (next ? "" : " disabled") + '">\
        <a class="page-link" href="' + (next ? next : "#") + '" data-dt-idx="2" tabindex="0">\
            <span class="d-none d-sm-none d-md-block">' + __("Next") + '</span>\
            <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-angle-right"></i></span>\
        </a>\
    </li>\
    <li class="page-item last' + (last_enabled ? "" : " disabled") + '">\
        <a class="page-link" href="' + (last_enabled ? last : "#") + '" data-dt-idx="3" tabindex="0">\
            <span class="d-none d-sm-none d-md-block">' + __("Last") + '</span>\
            <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-angle-double-right"></i></span>\
        </a>\
    </li>';

    pagination += '</ul>';
    return pagination;
}

function getNoResults(title) {
    var title = title ? title : __('results');

    var no_results = '<div class="row vertical-center">\
        <div class="col-sm-12">\
            <svg width="100" height="100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="512" height="512" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve">\
                <g>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m8.05 21h-5.55c-1.379 0-2.5-1.121-2.5-2.5v-16c0-1.379 1.121-2.5 2.5-2.5h12c1.379 0 2.5 1.121 2.5 2.5v7.03c0 .276-.224.5-.5.5s-.5-.223-.5-.5v-7.03c0-.827-.673-1.5-1.5-1.5h-12c-.827 0-1.5.673-1.5 1.5v16c0 .827.673 1.5 1.5 1.5h5.55c.276 0 .5.224.5.5s-.224.5-.5.5z" fill="#212529"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m13.5 9h-10c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h10c.276 0 .5.224.5.5s-.224.5-.5.5z" fill="#212529"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m9.5 13h-6c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h6c.276 0 .5.224.5.5s-.224.5-.5.5z" fill="#212529"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m8.5 5h-5c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h5c.276 0 .5.224.5.5s-.224.5-.5.5z" fill="#212529"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m22.5 24h-11c-.827 0-1.5-.673-1.5-1.5 0-.294.081-.569.235-.799l5.488-8.981c.259-.441.75-.72 1.277-.72s1.018.279 1.281.728l5.495 8.992c.143.211.224.486.224.78 0 .827-.673 1.5-1.5 1.5zm-5.5-11c-.174 0-.334.09-.418.233l-5.505 9.008c-.055.082-.077.165-.077.259 0 .275.225.5.5.5h11c.275 0 .5-.225.5-.5 0-.094-.022-.177-.065-.24l-5.512-9.019c-.089-.151-.249-.241-.423-.241z" fill="#ffc107"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m17 20c-.276 0-.5-.224-.5-.5v-4c0-.276.224-.5.5-.5s.5.224.5.5v4c0 .276-.224.5-.5.5z" fill="#ffc107"/>\
                    <circle xmlns="http://www.w3.org/2000/svg" cx="17" cy="21.5" r=".5" fill="#ffc107"/>\
                </g>\
            </svg>\
            <div class="text-muted text-sm font-weight-normal mt-2">' + __("No") + ' ' + __(title) + ' ' + __("found") + '</div>\
        </div>\
    </div>';

    return no_results;
}

function getAddNewRecord(title, btn) {
    var add_new = '<div class="row vertical-center">\
        <div class="col-sm-12">\
            <svg width="100" height="100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="512" height="512" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">\
                <g>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m9.02 21h-6.52c-1.378 0-2.5-1.121-2.5-2.5v-16c0-1.379 1.122-2.5 2.5-2.5h12c1.378 0 2.5 1.121 2.5 2.5v6.06c0 .276-.224.5-.5.5s-.5-.224-.5-.5v-6.06c0-.827-.673-1.5-1.5-1.5h-12c-.827 0-1.5.673-1.5 1.5v16c0 .827.673 1.5 1.5 1.5h6.52c.276 0 .5.224.5.5s-.224.5-.5.5z" fill="#212529"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m13.5 9h-10c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h10c.276 0 .5.224.5.5s-.224.5-.5.5z" fill="#212529"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m9.5 13h-6c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h6c.276 0 .5.224.5.5s-.224.5-.5.5z" fill="#212529"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m8.5 5h-5c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h5c.276 0 .5.224.5.5s-.224.5-.5.5z" fill="#212529"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m17.5 24c-3.584 0-6.5-2.916-6.5-6.5s2.916-6.5 6.5-6.5 6.5 2.916 6.5 6.5-2.916 6.5-6.5 6.5zm0-12c-3.033 0-5.5 2.468-5.5 5.5s2.467 5.5 5.5 5.5 5.5-2.468 5.5-5.5-2.467-5.5-5.5-5.5z" fill="#007bff"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m17.5 21c-.276 0-.5-.224-.5-.5v-6c0-.276.224-.5.5-.5s.5.224.5.5v6c0 .276-.224.5-.5.5z" fill="#007bff"/>\
                    <path xmlns="http://www.w3.org/2000/svg" d="m20.5 18h-6c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h6c.276 0 .5.224.5.5s-.224.5-.5.5z" fill="#007bff"/>\
                </g>\
            </svg>\
            <div class="text-muted text-sm font-weight-normal mt-2">' + __("No") + ' ' + __(title) + ' ' + __("found") + '</div>\
            <div class="mt-2">' + btn + '</div>\
        </div>\
    </div>';

    return add_new;
}

function stickyRelocate() {
    var window_top = $(window).scrollTop();

    if ($('#sticky-anchor') && typeof $('#sticky-anchor').offset() !== "undefined") {
        var div_top = $('#sticky-anchor').offset().top;

        if (window_top > div_top) {
            $('#sticky').addClass('stick elevation-1');
            $('#sticky-anchor').height($('#sticky').outerHeight());
        } else {
            $('#sticky').removeClass('stick elevation-1');
            $('#sticky-anchor').height(0);
        }
    }
}

function prepareUrlParameters(url) {
    // get current URL if not specified
    if (!url) {
        url = window.location.href;
    }

    var url_components = url.split("?");

    if (url_components.length > 1) {
        url = [url_components.shift(), url_components.join('?')]
        var page_url = decodeURIComponent(url[1]);
        var params = {};
        var parameters = page_url.split('&');

        for (var i = 0; i < parameters.length; i++) {
            var parameter = parameters[i].split('=');
            var key = parameter[0];
            var value = parameter[1].trim();

            // parameter is an array
            if (key.indexOf('[') !== -1 && key.indexOf(']') !== -1) {
                var matches = key.match(/\[(.*?)\]/);
                key = key.substr(0, key.indexOf('['));

                if (matches) {
                    var array_idx = matches[1];
                }

                // parameter is an associative array
                if (array_idx) {
                    if (typeof params[key] === "undefined") {
                        params[key] = {};
                    }

                    params[key][array_idx] = value;
                }

                // parameter is an normal array
                else {
                    if (typeof params[key] === "undefined") {
                        params[key] = [];
                    }

                    params[key].push(value);
                }
            }
            else {
                params[key] = value;
            }
        }
    }
    else {
        params = null;
    }

    return params;
}

function getImage(path, width, height, quality, crop, align, sharpen) {
    var url = '';

    if (($.inArray(path.split("/").pop().split(".").pop(), ["svg", "webp"]) !== -1) || (!width && !height)) {
        url = base_url + '/storage' + path;
    }
    else {
        url = base_url + '/timthumb.php?src=' + base_url + '/storage' + path;

        if (width) {
            url += '&w=' + width; 
        }

        if (height && height > 0) {
            url += '&h=' + height;
        }

        if (quality) {
            url += '&q=' + quality;
        }
        else {
            url += '&q=95';
        }

        if (crop) {
            url += "&zc=" + crop;
        }

        if (align) {
            url += '&a=' + align;
        }

        if (sharpen) {
            url += "&s=" + sharpen;
        }
    }

    return url;
}

/**
    * @return str Translated text from window object;
*/
function __(text) {
    if (origin.locale != 'en' && typeof origin.translations[text] !== "undefined") {
        text = origin.translations[text];
    }

    return text;
}

/**
    * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
    * @param str $hex Colour as hexadecimal (with or without hash);
    * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
    * @return str Lightened/Darkend colour as hexadecimal (with hash);
*/
function luminance(hex, percent) {
    // validate hex string
    hex = String(hex).replace(/[^0-9a-f]/gi, '');
    if (hex.length < 6) {
        hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
    }
    percent = percent || 0;

    // convert to decimal and change luminosity
    var new_hex = "#", c, i;
    for (i = 0; i < 3; i++) {
        c = parseInt(hex.substr(i*2,2), 16);
        c = Math.round(Math.min(Math.max(0, c + (c * percent)), 255)).toString(16);
        new_hex += ("00"+c).substr(c.length);
    }

    return new_hex;
}

// Removes any white space to the right and left of the string
function trim(str) {
    return str.replace(/^\s+|\s+$/g, "");
}

// Removes any white space to the left of the string
function ltrim(str) {
    return str.replace(/^\s+/, "");
}

// Removes any white space to the right of the string
function rtrim(str) {
    return str.replace(/\s+$/, "");
}

// Is an object a string
function isString(obj) {
    return typeof (obj) == 'string';
}

// Is an object a email address
function isEmail(obj) {
    if (isString(obj)) {
        return obj.match(/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/ig);
    }
    else {
        return false;
    }
}

function isHexColor(str) {
    if (isString(str)) {
        return str.match(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/);
    }
    else {
        return false;
    }
}

// convert mysql date time to javascript date time
function mysqlDateTimeToJSDate(datetime) {
    // Split timestamp into [ Y, M, D, h, m, s ]
    var t = datetime.split(/[- :]/);
    return new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
}