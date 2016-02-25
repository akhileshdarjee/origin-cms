$( document ).ready(function() {

	beautify_list_view();
	enable_autocomplete();

	// refresh the list view
	$("#refresh_list").on("click", function() {
		refresh_table_list();
		$("#search_text").val("");
	});

	// on row click show the record form view
	$("table.list-view").on("click" , '.clickable_row', function(e) {
		if ($(e.target).attr('data-field-name') != "row_check" && e.target.type != "checkbox") {
			window.location = $(this).data("href");
		}
	});


	// Check all checkboxes in list view on parent check
	$(document).on('change', 'table thead [type="checkbox"]', function(e){
		e && e.preventDefault();
		var $table = $(e.target).closest('table'), $checked = $(e.target).is(':checked');
		$('tbody [type="checkbox"]', $table).prop('checked', $checked);
		toggle_action_button();
	});


	// toggle action button on list row check
	$(document).on('change', 'table tbody [type="checkbox"]', function(e){
		toggle_action_button();
	});


	// refresh the data list based on search criteria
	$("#search").on("click", function() {
		if ($.trim($("#search_text").val())) {
			refresh_table_list($("#search_text").val());
		}
		else {
			msgbox("Please enter any text in search box");
			$('#message-box').on('hidden.bs.modal', function (e) {
				$("#search_text").val("");
				$("#search_text").focus();
			});
		}
	});


	// on action button click
	$("#action-button").on("click", function() {
		if ($(this).data("action") == "new") {
			window.location = "/form" + table;
		}
		else {
			remove_selected_row_data();
		}
	});

	// set pagination attributes
	$(".pagination").attr("class", "pagination pagination-small m-t-none m-b-none");

	function refresh_table_list(search) {
		var data = {
			'module_name': table,
			'search': search ? search : ""
		}

		$.ajax({
			type: 'GET',
			url: app_route,
			data: data,
			dataType: 'json',
			success: function(data) {
				var list_columns = data['columns'];
				var list_rows = data['rows'];
				var list_title = data['title'];
				var list_link_field = data['link_field'];
				var list_module = data['module'];

				var list_table = $("table").attr("data-module", list_module);
				var list_records = "";

				if (list_rows.length > 0) {
					$.each(list_rows, function(index, row_data) {
						list_records += '<tr class="clickable_row" data-href="/form/' + list_module.toSnakeCase() + '/' + list_rows[index][list_link_field] + '">';
						list_records += '<td data-field-name="row_check"><input type="checkbox" name="post[]" value="' + (index + 2) + '"></td>';
						$.each(list_columns, function(index, column_name) {
							var field_value = row_data[column_name];
							list_records += '<td data-field-name="' + column_name + '">' + field_value + '</td>';
						});
						list_records += '</tr>';
					});
				}

				$(list_table).find('tbody').empty().append(list_records);
				beautify_list_view();
			}
		});
	}
});


// toggle action button
function toggle_action_button() {
	var button_element = $("#action-button");
	var button_action = $(button_element).data("action");
	var checked_length = $("table.list-view > tbody > tr").find("input[type='checkbox']:checked").length;

	toggle_check_all_box(checked_length);

	if (checked_length > 0) {
		$(button_element).data("action", "delete");
		$(button_element).attr("data-action", "delete");
		$(button_element).html("Delete");
		$(button_element).data("original-title", "Delete selected record(s)");
		$(button_element).attr("data-original-title", "Delete selected record(s)");
		$(button_element).removeClass("btn-primary");
		$(button_element).addClass("btn-danger");
	}
	else {
		$('#check-all').prop('checked', false);
		$(button_element).data("action", "new");
		$(button_element).attr("data-action", "new");
		$(button_element).html("New");
		var module_name = app_route.split("/").pop(-1).replace(/_/g, " ").toProperCase();
		$(button_element).data("original-title", "New " + module_name);
		$(button_element).attr("data-original-title", "New " + module_name);
		$(button_element).removeClass("btn-danger");
		$(button_element).addClass("btn-primary");
	}
}


// delete all records based on rows checked
function remove_selected_row_data() {
	var checked_rows = get_checked_rows();
	if (checked_rows.length > 0) {
		var modal_footer = '<button type="button" class="btn btn-white" data-dismiss="modal">No</button>\
			<button type="button" class="btn btn-danger" data-action="delete" id="list-delete">Yes</button>';
		msgbox("Sure you want to permanently delete selected record(s) ?", modal_footer);
	}
	else {
		msgbox("Please select any record to delete.");
	}

	$("#list-delete").on("click", function() {
		var data = {
			"module_name": table,
			"delete_list": checked_rows
		};

		$.ajax({
			type: 'GET',
			url: app_route,
			data: data,
			success: function(data) {
				msgbox("Record(s) deleted successfully");
				update_record_count();
				$.each(checked_rows, function(index, row) {
					$('tr[data-href="' + row + '"]').remove();
				});
				toggle_action_button();
			},
			error: function(data) {
				msgbox("Some problem occured. Please try again");
			}
		});
	});
}


// get all checked rows
function get_checked_rows() {
	var checked_rows = [];
	$.each($("table.list-view > tbody > tr").find("input[type='checkbox']"), function(index, element) {
		if ($(element).is(":checked")) {
			var link_field = $(element).data("link-field");
			if (checked_rows.contains(link_field)) {
				checked_rows.push($(this).closest('tr.clickable_row').data('href'));
			}
		}
	});

	return checked_rows;
}


// toggle check all checkbox
function toggle_check_all_box(checked_length) {
	var total_check_boxes = $("table.list-view > tbody > tr").find("input[type='checkbox']").length;
	if (!checked_length) {
		var checked_length = $("table.list-view > tbody > tr").find("input[type='checkbox']:checked").length;
	}

	if (checked_length && checked_length == total_check_boxes) {
		$('#check-all').prop('checked', true);
	}
	else {
		$('#check-all').prop('checked', false);		
	}
}


// update record count after row actions
function update_record_count() {
	var checked_length = $("table.list-view > tbody > tr").find("input[type='checkbox']:checked").length;
	var total_records = parseInt($("#row-count").html());

	$("#row-count").html(total_records - checked_length);
}