$( document ).ready(function() {
	$(".new_row").on("click", function() {
		var table = $("#" + $(this).data("target"));
		add_new_row(table);
		$(table).find("tr:last > td:eq(3) > input").focus();
	});


	// remove row
	$("table").on("click" , '#remove_row', function() {
		var target = $(this).closest("table").attr("id");
		var tbody = $(this).closest("table").find("tbody");
		var table = $(this).closest("table");

		if ($("#id").val()) {
			// if ID present the record already exists in db
			// At that time if row is deleted then set action to delete and hide the row
			$(this).closest("tr.table_record").find("td#action").find("input").val("delete");
			$(this).closest("tr.table_record").hide();
		}
		else {
			// if ID is not present then simply remove row which will be independent of action
			$(this).closest("tr").remove();
		}

		// show total no of row badge
		show_total_badge(target);

		if ($(tbody).find("tr").length) {
			maintain_idx(tbody);
		}
		else {
			show_empty_row(table);
		}

		enable_save_button();
	});


	// make row editable
	$("table").on("click", '.table_record', function() {
		$(this).find("input").removeClass("simple-box");
	});

	// on input blur make row as simplebox
	$("table").on("blur", 'input', function() {
		$.each($(this).closest("table").find("tbody > tr"), function() {
			$(this).find("input").addClass("simple-box");
		});

		$(this).find("input").removeClass("simple-box");
	});


	// set action update if input is changed
	$("table > tbody > tr").on("change", 'input', function() {
		if ($("#id").val()) {
			$(this).closest("tr").find("td#action > input").val("update");
		}
	});
});


function add_new_row(table, idx, action) {
	var thead = $(table).find("thead");
	var tbody = $(table).find("tbody");

	// remove empty row
	if ($(tbody).find("tr").hasClass("odd")) {
		$(tbody).empty();
	}

	if ($(tbody).find("tr").length) {
		set_row_after_input(tbody);
	}

	// add row html
	add_row(table, idx ? idx : $(tbody).find("tr").length + 1, action);
	show_total_badge($("." + $(table).attr("id")).find(".new_row").data("target"));
}


function add_row(table, idx, action) {
	var table_name = $(table).data("table");
	var thead = $(table).find("thead");
	var tbody = $(table).find("tbody");
	var row_action = action ? action : "create";

	var rows = '<tr class="table_record">';

	$.each($(thead).find("tr > th"), function(index, heads) {
		if ($(heads).attr("id") == "sr_no" && index == 0) {
			rows += '<td class="text-center"></td>';
		}
		else if ($(heads).attr("id") == "remove") {
			rows += '<td id="remove_row" class="text-center" style="cursor: pointer;" data-idx="' + idx + '">\
				<i class="fa fa-times fa-lg text-danger text"></i></td>';
		}
		else if ($(heads).attr("id") == "action") {
			rows += '<td id="action" style="display: none;">\
				<input type="text" class="form-control input-sm" name="' + table_name + '[' + (idx - 1) + '][action]" value="' + row_action + '">\
				</td>';

			$(this).find('input[name="' + table_name + '[' + (idx - 1) + '][action]"]').val(row_action);
		}
		else if ($(heads).attr("id") == "row_id") {
			rows += '<td id="row_id" style="display: none;">\
				<input type="text" class="form-control input-sm" name="' + table_name + '[' + (idx - 1) + '][id]">\
				</td>';
		}
		else {
			var field_type = $(heads).data("field-type");
			var field_name = $(heads).data("field-name");
			var target_module = $(heads).data("target-module");
			var target_field = $(heads).data("target-field");
			var readonly = ($(heads).data("readonly") == "yes") ? "readonly" : "";
			var hidden = ($(heads).data("hidden") == "yes") ? "style='display: none;'" : "";

			if (field_type == "link") {
				rows += '<td data-field-type="link">\
					<input type="text" class="form-control input-sm autocomplete" \
					name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" \
					autocomplete="off" data-target-module="' + target_module + '" data-target-field="' + target_field + '"' + readonly + '>\
					</td>';
			}
			else if (field_type == "select") {
				rows += '<td data-field-type="select">\
					<select class="form-control input-sm" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']">';

				$.each($(heads).data("options").split(","), function(index, option) {
					rows += '<option value="' + option + '">' + option + '</option>';
				});

				rows += '</select></td>';
			}
			else if (field_type == "text" || field_type == "money") {
				rows += '<td data-field-type="' + field_type + '"' + hidden + '>\
					<input type="text" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" \
					class="form-control input-sm" data-target-module="' + target_module + '" data-target-field="' + target_field + '" autocomplete="off"' + readonly + '>\
					</td>';
			}
		}
	});
	rows += '</tr>';

	$(tbody).append(rows);
	maintain_idx(tbody);
	enable_autocomplete();
}


function maintain_idx(tbody) {
	var idx = 1;
	$.each($(tbody).find("tr"), function(index, row) {
		if ($(row).is(":visible")) {
			$(row).attr("idx", idx);
			$(row).find("td:first").html(idx);
			idx++;
		}
	});
}


function show_empty_row(table) {
	var colspan = $(table).find("thead > tr > th").length;
	var empty_row = '<tr class="odd">\
		<td valign="middle" align="center" colspan="' + colspan + '">Empty</td>\
	</tr>';
	$(table).find("tbody").append(empty_row);
}


function set_row_after_input(tbody) {
	$.each($(tbody).find("tr > td"), function(index, col) {
		$(col).find("input").addClass("simple-box");
	});
}


function show_total_badge(target) {
	var total_rows = $("." + target).find("table#" + target).find("tbody > tr:visible").length;
	$("." + target).find("#total_badge").html(total_rows);
}