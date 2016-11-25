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
			// if ID is present, means the record already exists in db
			// At that time if row is deleted then set action as delete and hide the row
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
	$("table").on("blur", 'input, select, textarea', function() {
		$.each($(this).closest("table").find("tbody > tr"), function() {
			$(this).find("input").addClass("simple-box");
		});

		$(this).find("input").removeClass("simple-box");
	});


	// set action update if input is changed
	$("table > tbody > tr").on("change", 'input, select, textarea', function() {
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
	var field_types = [];

	var row = '<tr class="table_record">';

	$.each($(thead).find("tr > th"), function(index, heads) {
		if ($(heads).attr("id") == "sr_no" && index == 0) {
			row += '<td class="text-center"></td>';
		}
		else if ($(heads).attr("id") == "remove") {
			row += '<td class="text-center" data-idx="' + idx + '">\
				<button type="button" class="btn btn-danger" id="remove_row">\
					<i class="fa fa-times"></i>\
				</button>\
			</td>';
		}
		else if ($(heads).attr("id") == "action") {
			row += '<td id="action" style="display: none;">\
				<input type="text" class="form-control input-sm" name="' + table_name + '[' + (idx - 1) + '][action]" value="' + row_action + '">\
			</td>';

			$(this).find('input[name="' + table_name + '[' + (idx - 1) + '][action]"]').val(row_action);
		}
		else if ($(heads).attr("id") == "row_id") {
			row += '<td id="row_id" style="display: none;">\
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

			field_types.push(field_type);

			if (field_type == "link") {
				row += '<td data-field-type="link">\
					<input type="text" class="form-control input-sm autocomplete" \
					name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" \
					autocomplete="off" data-target-module="' + target_module + '" data-target-field="' + target_field + '"' + readonly + '>\
				</td>';
			}
			else if (field_type == "avatar") {
				row += '<td data-field-type="avatar">\
					<div class="col-md-12 media">\
						<div class="pull-left text-center avatar-box">\
							<i class="fa fa-picture-o inline fa-2x avatar"></i>\
						</div>\
						<div class="media-body text-left">\
							<label title="Upload image file" class="btn btn-primary btn-xs">\
								<input type="file" accept="image/*" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" class="hide">\
								Change\
							</label>\
						</div>\
					</div>\
				</td>';
			}
			else if (field_type == "select") {
				row += '<td data-field-type="select">\
					<select class="form-control input-sm" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']">';

				$.each($(heads).data("options").split(","), function(index, option) {
					row += '<option value="' + option + '">' + option + '</option>';
				});

				row += '</select></td>';
			}
			else if (field_type == "time") {
				row += '<td data-field-type="time">\
					<div class="input-group clockpicker" data-autoclose="true">\
						<span class="input-group-addon">\
							<i class="fa fa-clock-o"></i>\
						</span>\
						<input type="text" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" class="form-control input-sm" autocomplete="off">\
					</div>\
				</td>';
			}
			else if (field_type == "text" || field_type == "money") {
				if (target_module && target_field) {
					row += '<td data-field-type="' + field_type + '"' + hidden + '>\
						<input type="text" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" \
						class="form-control input-sm" data-target-module="' + target_module + '" data-target-field="' + target_field + '" autocomplete="off"' + readonly + '>\
					</td>';
				}
				else {
					row += '<td data-field-type="' + field_type + '"' + hidden + '>\
						<input type="text" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" \
						class="form-control input-sm" autocomplete="off"' + readonly + '>\
					</td>';
				}
			}
			else if (field_type == "textarea") {
				row += '<td data-field-type="textarea">\
					<textarea rows="5" cols="8" name="' + table_name + '[' + (idx - 1) + '][' + field_name + ']" \
					class="form-control input-sm" autocomplete="off"></textarea>\
				</td>';
			}
		}
	});
	row += '</tr>';

	$(tbody).append(row);
	maintain_idx(tbody);
	enable_autocomplete();

	if (field_types.contains("time")) {
		$.each($("table > tbody > tr").find(".clockpicker"), function(idx, element) {
			$(element).clockpicker();
			$(element).find("input").on("change", function() {
				$(this).closest("tr").find("td#action > input").val("update");
			});
		});
	}
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


// add multiple rows for table at the time of loading
function add_new_rows(table_name, records) {
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
			var target_module = $(heads).data("target-module");
			var target_field = $(heads).data("target-field");
			var readonly = ($(heads).data("readonly") == "yes") ? "readonly" : "";
			var hidden = ($(heads).data("hidden") == "yes") ? "style='display: none;'" : "";
			field_types.push(field_type);

			// get value for the field
			if (value[field_name] && typeof value[field_name] === 'string' && (value[field_name].isDate() || value[field_name].isDateTime())) {
				if (child_value.split(" ").length > 1) {
					field_value = moment(value[field_name]).format('DD-MM-YYYY hh:mm A');
				}
				else {
					field_value = moment(value[field_name]).format('DD-MM-YYYY');
				}
			}
			else if (value[field_name] && typeof value[field_name] === 'string' && value[field_name].isTime()) {
				field_value = moment(value[field_name], ["HH:mm:ss"]).format('HH:mm');
			}
			else {
				field_value = value[field_name] || '';
			}

			// set default table values
			if ($(heads).attr("id") == "sr_no") {
				rows += '<td class="text-center" style="vertical-align: middle;">' + (idx + 1) + '</td>';
			}
			else if ($(heads).attr("id") == "remove") {
				rows += '<td class="text-center" data-idx="' + (idx + 1) + '">\
					<button type="button" class="btn btn-danger" id="remove_row">\
						<i class="fa fa-times"></i>\
					</button>\
				</td>';
			}
			else if ($(heads).attr("id") == "action") {
				// while showing data
				if (value["id"]) {
					var action = "none";
				}
				// while copying data
				else {
					var action = "create";
				}

				rows += '<td id="action" style="display: none;">\
					<input type="text" class="form-control input-sm" name="' + table_name + '[' + idx + '][action]" value="' + action + '">\
				</td>';
			}
			else if ($(heads).attr("id") == "row_id") {
				rows += '<td id="row_id" style="display: none;">\
					<input type="text" class="form-control input-sm" name="' + table_name + '[' + idx + '][id]" value="' + value["id"] + '">\
				</td>';
			}
			// set field value
			else {
				if (field_type == "link") {
					rows += '<td data-field-type="link">\
						<input type="text" class="form-control input-sm autocomplete" \
						name="' + table_name + '[' + idx + '][' + field_name + ']" \
						autocomplete="off" data-target-module="' + target_module + '" data-target-field="' + target_field + '"' + readonly + ' value="' + field_value + '">\
					</td>';
				}
				else if (field_type == "avatar") {
					rows += '<td data-field-type="avatar">\
						<div class="col-md-12 media">\
							<div class="pull-left text-center avatar-box">\
								<i class="fa fa-picture-o inline fa-2x avatar"></i>\
							</div>\
							<div class="media-body text-left">\
								<label title="Upload image file" class="btn btn-primary btn-xs">\
									<input type="file" accept="image/*" name="' + table_name + '[' + idx + '][' + field_name + ']" class="hide">\
									Change\
								</label>\
							</div>\
						</div>\
					</td>';
				}
				else if (field_type == "select") {
					rows += '<td data-field-type="select">\
						<select class="form-control input-sm" name="' + table_name + '[' + idx + '][' + field_name + ']">';

					$.each($(heads).data("options").split(","), function(index, option) {
						if (option == value[field_name]) {
							rows += '<option value="' + option + '" default selected>' + option + '</option>';
						}
						else {
							rows += '<option value="' + option + '">' + option + '</option>';
						}
					});

					rows += '</select></td>';
				}
				else if (field_type == "time") {
					rows += '<td data-field-type="time">\
						<div class="input-group clockpicker" data-autoclose="true">\
							<span class="input-group-addon">\
								<i class="fa fa-clock-o"></i>\
							</span>\
							<input type="text" name="' + table_name + '[' + idx + '][' + field_name + ']" class="form-control input-sm" autocomplete="off" value="' + field_value + '">\
						</div>\
					</td>';
				}
				else if (field_type == "text" || field_type == "money") {
					if (target_module && target_field) {
						rows += '<td data-field-type="' + field_type + '"' + hidden + '>\
							<input type="text" name="' + table_name + '[' + idx + '][' + field_name + ']" \
							class="form-control input-sm" data-target-module="' + target_module + '" data-target-field="' + target_field + '" autocomplete="off"' + readonly + ' value="' + field_value + '">\
						</td>';
					}
					else {
						rows += '<td data-field-type="' + field_type + '"' + hidden + '>\
							<input type="text" name="' + table_name + '[' + idx + '][' + field_name + ']" \
							class="form-control input-sm" autocomplete="off"' + readonly + ' value="' + field_value + '">\
						</td>';
					}
				}
				else if (field_type == "textarea") {
					rows += '<td data-field-type="textarea">\
						<textarea rows="5" cols="8" name="' + table_name + '[' + idx + '][' + field_name + ']" \
						class="form-control input-sm" autocomplete="off">' + field_value + '</textarea>\
					</td>';
				}
			}
		});

		rows += '</tr>';

		if (tbody_len) {
			tbody_len++;
		}
	});

	$(tbody).append(rows);
	enable_autocomplete();

	if (field_types.contains("time")) {
		$.each($("table > tbody > tr").find(".clockpicker"), function(idx, element) {
			$(element).clockpicker();
			$(element).find("input").on("change", function() {
				$(this).closest("tr").find("td#action > input").val("update");
			});
		});
	}
}