// convert string to proper case
String.prototype.toProperCase = function () {
	return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};

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


// set common global variables
var app_route = window.location.pathname;
var table = "/" + app_route.split("/").pop(-1);
var form_changed = false;

var money_list = ['total_amount', 'grand_total', 'rate', 'food_total_amount', 'laundry_total_amount'];
var label_list = ['status', 'role'];
var label_bg = {
	status : {Active : 'bg-success', Inactive : 'bg-danger', Vacant : 'bg-success', Occupied : 'bg-danger'},
	role : {Administrator : 'bg-inverse', Client : 'bg-primary', Cook : 'bg-warning', Guest : 'bg-info'}
}

// Setup ajax for making csrf token used by laravel
$(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
});

$( document ).ready(function() {
	// set body data url
	$("body").attr("data-url", app_route);

	// toggle breadcrumb
	var breadcrumb_ignore_list = ['/app', '/list', '/login', '/password'];
	var bread = true;

	$.each(breadcrumb_ignore_list, function(index, ignored) {
		if (app_route.indexOf(ignored) >= 0) {
			bread = false;
			return false;
		}
	});

	if (bread) {
		var module_name = app_route.split("/")[2];

		var breadcrumb = '<a class="navbar-brand" href="/list/' + module_name + '" style="font-size: 22px;">' + module_name.replace(/_/g, " ").toProperCase() + ' List</a>';
		$(".navbar-brand").addClass("hidden-xs hidden-sm");
		$(breadcrumb).insertAfter(".navbar-brand");
	}
	else {
		if ($(".navbar-brand").length > 1) {
			$(".navbar-brand:last").remove();
		}
	}


	// toggle vertical nav active
	$.each($("nav > ul > li"), function(index, navbar) {
		if ($(this).find('a').attr('href') == app_route) {
			$(this).addClass('active');
		}
		else {
			$(this).removeClass('active');
		}
	});
	

	// enable datepicker
	$(function () {
		$(".datepicker").datepicker({
			'format': 'dd-mm-yyyy'
		});
	});

	// allow only numbers to text box
	$('body').on('input change', '.numbers-only', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
});


// Autocomplete
function enable_autocomplete() {
	var autocomplete = $('.autocomplete');
	$.each(autocomplete, function(index, field) {
		var data_module = $(field).data("target-module");
		var data_field = $(field).data("target-field");
		var item_data = {};
		var module_fields = {};
		$.each($("form").find('input[data-target-module="' + data_module + '"]'), function(index, element) {
			if (module_fields[data_module]) {
				module_fields[data_module].push($(element).data("target-field"));
			}
			else {
				module_fields[data_module] = [$(element).data("target-field")];
			}
		});

		$(this).attr('data-target-field', data_field).typeahead({
			onSelect: function(item) {
				var selected_item_data = {};
				$.each(item_data, function(index, value) {
					if (item.text == item_data[index][data_field]) {
						selected_item_data = value;
					}
				});

				delete selected_item_data[data_field];

				$.each(selected_item_data, function(key, value) {
					var input_field = $('form').find('input[data-target-field="' + key + '"]');
					length = (input_field).length;
					if (length > 1) {
						$(input_field).last().val(value);
					}
					else {
						$(input_field).val(value);
					}

					initialize_mandatory_fields();
					remove_mandatory_highlight(mandatory_fields);
				});
			},
			displayField: data_field,
			ajax: {
				url: '/getAutocomplete',
				preDispatch: function (query) {
					return {
						module: data_module,
						field: data_field,
						fetch_fields: module_fields[data_module]
					}
				},
				preProcess: function (data) {
					if (data.success === false) {
						// Hide the list, there was some error
						return false;
					}
					item_data = data;
					return data;
				},
				triggerLength: 1
			},
			scrollBar: true
		});
	});
}


// msgbox
function msgbox(msg, footer, title) {
	$("#message-box").on("show.bs.modal", function (e) {
		$(".modal-title").html(title ? title : "Message");
		$(".modal-body").html(msg);
		if (footer) {
			$(".modal-footer").html(footer);
			$(".modal-footer").show();
		}
		else {
			$(".modal-footer").html("");
			$(".modal-footer").hide();
		}
	})
	.on('hidden.bs.modal', function (e) {
		$(".modal-title").html("Message");
		$(".modal-body").html("");
		$(".modal-footer").html("");
		$(".modal-footer").hide();
	});

	$('#message-box').modal('show');
}

// set date to the element
function set_date(date_element, date, date_format, add_days) {
	if (!$(date_element).val()) {
		var date = date ? date : new Date();
		var date_format = date_format ? date_format : 'DD-MM-YYYY hh:mm A';

		if (add_days) {
			var formatted_date = moment(date).add(add_days, 'days').format(date_format);
		}
		else {
			var formatted_date = moment(date).format(date_format);
		}

		$(date_element).val(formatted_date);
		$(date_element).closest("div.form-group").removeClass("has-error");
	}
}


// add status labels, icon for money related fields
function beautify_list_view(table) {

	// field defaults
	var money_list = ['total_amount', 'grand_total', 'rate', 'food_total_amount', 'laundry_total_amount', 'debit', 'credit'];
	var contact_list = ['contact_no', 'phone_no'];
	var address_list = ['address', 'full_address', 'city', 'venue'];
	var email_list = ['email_id', 'guest_id'];
	var label_list = ['status', 'role'];
	var label_bg = {
		'status' : { 'Active' : 'bg-success', 'Inactive' : 'bg-danger', 'Vacant' : 'bg-success', 'Occupied' : 'bg-danger' }, 
		'role' : { 'Administrator' : 'bg-inverse', 'Basecamp Admin' : 'bg-success', 'Client' : 'bg-primary', 'Cook' : 'bg-warning', 'Guest' : 'bg-info' }
	}

	var table = table ? table : "table.list-view";
	var thead = $(table).find("thead");
	var tbody = $(table).find("tbody");

	// make table heading
	$.each($(thead).find("tr > th"), function() {
		if ($(this).attr("name")) {
			var heading_name = $(this).attr("name");
			var heading = heading_name.replace(/_/g, " ").toProperCase();
			if (heading.indexOf("Id") > -1) {
				heading = heading.replace("Id", "ID");
			}

			if ($.inArray(heading_name, money_list) >= 0) {
				$(this).html(heading + ' (<i class="fa fa-inr"></i>)');
			}
			else {
				$(this).html(heading);
			}
		}
	});

	if ($(tbody).find("tr").length < 1) {
		row = '<tr>\
			<td style="text-align:center; padding:10px; border-bottom:none;" colspan="' + $(thead).find("tr > th").length + '">\
				<div class="h4"><strong>No Data</strong></div>\
			</td>\
		</tr>';
		$(table).find('tbody').empty().append(row);
	}
	else {
		// make table rows
		$.each($(tbody).find("tr > td"), function() {
			if ($(this).attr("data-field-name")) {
				var column_name = $(this).attr("data-field-name");
				var column_value = $.trim($(this).html());
				if ($.trim(column_value) != "") {
					if ($.inArray(column_name, money_list) >= 0) {
						$(this).html('<i class="fa fa-inr"></i> ' + column_value);
					}
					else if ($.inArray(column_name, contact_list) >= 0) {
						$(this).html('<i class="fa fa-phone"></i> ' + column_value);
					}
					else if ($.inArray(column_name, address_list) >= 0) {
						$(this).html('<i class="fa fa-map-marker"></i> ' + column_value);
					}
					else if ($.inArray(column_name, email_list) >= 0) {
						$(this).html('<i class="fa fa-envelope"></i> ' + column_value);
					}
					else if ($.inArray(column_name, label_list) >= 0) {
						$(this).html('<span class="label ' + label_bg[column_name][column_value] + '">' + column_value +  '</span>');
					}
					else if (column_name.includes("date")) {
						$(this).html('<i class="fa fa-calendar"></i> ' + moment(column_value).format('DD-MM-YYYY'));
					}
				}
			}
		});
	}
}