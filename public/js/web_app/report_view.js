$( document ).ready(function() {
	var report_table = $('table.report-table').DataTable({
		"processing": true
	});

	if ($("#from_date") && $("#to_date")) {
		$(function () {
			$("#fromdate").on("dp.change", function (e) {
				$("#todate").data("DateTimePicker").minDate(e.date);
			});
		});
	}

	// refresh the grid view of report
	$("#apply_filters").on("click", function() {
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
				var grid_rows = data;

				// clear the datatable
				report_table.clear().draw();

				if (grid_rows.length > 0) {
					// add each row to datatable using api
					$.each(grid_rows, function(grid_index, grid_data) {
						var record = [];
						record.push(grid_index + 1);

						$.each(grid_data, function(column_name, column_value) {
							record.push(column_value);
						});

						// add new row to datatable using api
						report_table.row.add(record).draw('false');
					});
				}

				$('#item-count').html(grid_rows.length);
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
});