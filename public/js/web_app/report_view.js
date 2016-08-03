$( document ).ready(function() {

	var report_table = $('table#report-table').DataTable({
		"bProcessing": true,
		"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
		"sPaginationType": "full_numbers",
		"bAutoWidth": false,
		"oLanguage": {
			"sEmptyTable": "No Data",
			"sProcessing": '<div class="sk-spinner sk-spinner-double-bounce"><div class="sk-double-bounce1"></div><div class="sk-double-bounce2"></div></div>'
		},
		"scrollY": 375,
		"scrollX": true,
	});


	// make theads scrollable
	$.each($("table#report-table > thead > tr > th"), function(idx, heading) {
		$(heading).resizable({
			handles: "e",
			create: function(event, ui) {
				$(".ui-resizable-e").css("cursor","col-resize");
			}
		});
	});

	enable_autocomplete();

	// make search and show entries element as per bootstrap
	$("#report-table_filter").find("input").addClass("form-control");
	$("#report-table_filter").find("input").attr("title", "Search in table");
	$("#report-table_filter").find("input").tooltip({
		"container": 'body',
		"placement": 'bottom',
	});
	$("#report-table_length").find("select").addClass("form-control");


	if ($("#from_date") && $("#to_date")) {
		$(function () {
			$("#fromdate").on("dp.change", function (e) {
				$("#todate").data("DateTimePicker").minDate(e.date);
			});
		});
	}


	// refresh the grid view of report
	$("#refresh_report").on("click", function() {
		var filter_found = false;
		$.each($("#report-filters").find("input, select"), function() {
			if ($(this).val()) {
				filter_found = true;
			}
		});

		if (filter_found) {
			refresh_grid_view();
		}
		else {
			msgbox("Please set any filter value");
		}
	});


	// download the report
	$("#download_report").on("click", function() {
		var filters = "";

		$.each($("#report-filters").find("input, select"), function() {
			if ($(this).attr("name") && $(this).val()) {
				filters += '&filters[' + $(this).attr("name") + ']=' + encodeURIComponent($(this).val().toString());
			}
		});

		window.location = app_route + "?download=Yes" + filters;
	});


	function refresh_grid_view() {
		$.ajax({
			type: 'GET',
			url: app_route,
			data: { 'filters': get_report_filters() },
			dataType: 'json',
			success: function(data) {
				var grid_rows = data['rows'];
				var columns = data['columns'];
				var rows = [];

				$.each(grid_rows, function(grid_index, grid_data) {
					var row = {};
					$.each(columns, function(idx, column) {
						row[column] = grid_data[column];
					});

					rows.push(row);
				});

				// clear the datatable
				report_table.clear().draw();

				if (rows.length > 0) {
					// add each row to datatable using api
					$.each(rows, function(grid_index, grid_data) {
						var record = [];
						record.push(grid_index + 1);

						$.each(grid_data, function(column_name, column_value) {
							if (data['module'] && data['link_field'] && data['record_identifier'] && (data['record_identifier'] == column_name)) {
								column_value = '<a href="/form/' + data["module"].toSnakeCase() + '/' + grid_data[data["link_field"]] + '">' + column_value + '</a>';
							}

							if (columns.contains(column_name)) {
								record.push(column_value);
							}
						});

						// add new row to datatable using api
						report_table.row.add(record).draw('false');
					});
				}

				$('#item-count').html(rows.length);
			}
		});
	}


	// returns the filters for report
	function get_report_filters() {
		var filters = {};

		$.each($("#report-filters").find("input, select"), function() {
			if ($(this).attr("name") && $(this).val()) {
				filters[$(this).attr("name")] = $(this).val();
			}
		});

		return filters;
	}


	$('table').find('.dataTables_empty').html("No Data");
});