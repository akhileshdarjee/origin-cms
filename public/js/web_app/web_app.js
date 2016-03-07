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

	$('a.back-to-top').click(function() {
		$('body, html').animate({
			scrollTop: 0
		}, 400);
		return false;
	});


	// set today date on event button
	var today_date = new Date();
	var date_element = '<span class="button-date">' + ("0" + today_date.getDate()).slice(-2) + '</span>';
	$('button[data-href="/List/Event"]').append(date_element);


	// toggle breadcrumb
	var breadcrumb_ignore_list = ['/App', '/App/Modules', '/App/Dashboard', '/App/Charts', '/App/Reports', '/login', '/step'];
	if ((breadcrumb_ignore_list.indexOf(app_route) >= 0) || app_route.split("/")[1] == "List") {
		if ($(".navbar-brand").length > 1) {
			$(".navbar-brand:last").remove();
		}
	}
	else {
		var breadcrumb = '<a class="navbar-brand" href="/List/' + app_route.split("/")[1] + '" style="font-size: 22px;">' + app_route.split("/")[1] + ' List</a>';
		$(".navbar-brand").addClass("hidden-xs hidden-sm");
		$(breadcrumb).insertAfter(".navbar-brand");
	}


	// toggle vertical nav active
	$.each($("nav > div > ul > li"), function(index, navbar) {
		if ($(this).find('a').attr('href') == app_route) {
			$(this).addClass('active');
		}
		else {
			$(this).removeClass('active');
		}
	});


	// module click navigate to list view
	$(".module-btn").on("click", function() {
		window.location = $(this).data("href");
	});


	// enable date picker
	$(function () {
		$(".date").datepicker({
			format: 'dd-mm-yyyy',
			todayBtn: "linked",
			keyboardNavigation: false,
			forceParse: false,
			autoclose: true
		});
	});


	// enable datetime picker
	$(function () {
		$(".datetimepicker").datetimepicker({
			icons: {
				time: 'fa fa-clock-o',
				date: 'fa fa-calendar',
				up: 'fa fa-chevron-up',
				down: 'fa fa-chevron-down',
				previous: 'fa fa-chevron-left',
				next: 'fa fa-chevron-right',
				today: 'fa fa-crosshairs',
				clear: 'fa fa-trash',
				close: 'fa fa-times'
			},
			format: 'DD-MM-YYYY hh:mm A',
			allowInputToggle: true,
		});
	});

	// Autocomplete
	enable_autocomplete();

	// allow only numbers to text box
	$('.numbers-only').on("input change", function() { 
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
	var money_list = [
		'amount', 'sub_total', 'total_amount', 'grand_total', 'rate', 'price', 'selling_price', 
		'cost', 'mrp', 'delivery_charges', 'paid_amount'
	];
	var contact_list = ['contact_no', 'phone_no', 'mobile', 'mobile_no', 'phone', 'contact'];
	var address_list = ['address', 'full_address', 'permanent_address'];
	var email_list = ['email_id', 'email'];
	var detail_list = ['description'];
	var label_list = ['status', 'role', 'gender', 'customer_type'];
	var percent_list = ['female_margin_percentage', 'male_margin_percentage'];

	var label_bg = {
		'gender': {
			'Male': 'label-info', 
			'Female': 'label-warning'
		},
		'status': {
			'Active' : 'label-success', 
			'Inactive' : 'label-danger', 
			'Vacant' : 'label-success', 
			'Occupied' : 'label-danger'
		}, 
		'role': {
			'Administrator' : 'label-primary', 
			'Customer' : 'label-default', 
			'Inventory Manager' : 'label-primary', 
			'Customer Service Manager' : 'label-warning', 
			'Product Content Manager' : 'label-info', 
			'Experience Content Manager' : 'label-success', 
			'Marketing Manager' : 'label-inverse'
		},
		'customer_type': {
			'Individual': 'label-warning',
			'Organisation': 'label-success'
		}
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
			if ($(this).attr("data-field-name") && $.trim($(this).html())) {
				var column_name = $(this).attr("data-field-name");
				var column_value = $(this).html();
				if ($.inArray(column_name, money_list) >= 0) {
					$(this).html('<i class="fa fa-inr iconify"></i> ' + column_value);
				}
				else if ($.inArray(column_name, contact_list) >= 0) {
					$(this).html('<i class="fa fa-phone iconify"></i> ' + column_value);
				}
				else if ($.inArray(column_name, address_list) >= 0) {
					$(this).html('<i class="fa fa-map-marker iconify"></i> ' + column_value);
				}
				else if ($.inArray(column_name, email_list) >= 0) {
					$(this).html('<i class="fa fa-envelope iconify"></i> ' + column_value);
				}
				else if ($.inArray(column_name, detail_list) >= 0) {
					$(this).html('<i class="fa fa-align-justify iconify"></i> ' + column_value);
				}
				else if ($.inArray(column_name, label_list) >= 0) {
					$(this).html('<span class="label ' + label_bg[column_name][column_value] + '">' + column_value +  '</span>');
				}
				else if ($.inArray(column_name, percent_list) >= 0) {
					$(this).html('<small>' + column_value + '%</small>\
						<div class="progress progress-mini">\
							<div style="width: ' + column_value + '%;" class="progress-bar"></div>\
						</div>'
					);
				}
				else if (column_name.includes("date")) {
					$(this).html('<i class="fa fa-calendar iconify"></i> ' + moment(column_value).format('DD-MM-YYYY hh:mm A'));
				}
			}
		});
	}
}