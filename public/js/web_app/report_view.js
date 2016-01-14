$( document ).ready(function() {

	beautify_list_view('table#report-table');

	// $('[data-ride="dataTable"]').DataTable({
	// 	"bProcessing": true,
	// 	"sScrollY": "300px",
	// 	"bScrollCollapse": true,
	// 	"bSort": true,
	// 	"bPaginate": false,
	// 	"bAutoWidth": false,
	// 	"bScrollAutoCss": false,
	// 	"oSearch": false,
	// 	"bInfo": false
	// });

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
			var loading = '<tr class="text-center">\
					<td colspan="' + $("table.datagrid").find("thead > tr > th").length + '">\
						<div class="col-md-12"><i class="fa fa-circle-o-notch fa-spin fa-3x"></i></div>\
					</td>\
				</tr>';

			$('table.datagrid').find('tbody').empty().append(loading);
			refresh_grid_view();
		}
		else {
			msgbox("Please set any filter value");
		}
	});


	// download the report
	$("#download_report").on("click", function() {
		var filters = "";

		$.each($("#report-filters").find("input"), function() {
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
				var grid_records = "";

				if (grid_rows.length > 0) {
					$.each(grid_rows, function(grid_index, grid_data) {
						grid_records += '<tr>';
						grid_records += '<td>' + (grid_index + 1) + '</td>';
						$.each(grid_data, function(column_name, column_value) {
							if (column_value) {
								grid_records += '<td data-field-name="' + column_name + '">' + column_value + '</td>';
							}
							else {
								grid_records += '<td data-field-name="' + column_name + '"></td>';
							}
						});
						grid_records += '</tr>';
					});
				}

				$('table#report-table').find('tbody').empty().append(grid_records);
				$('#item-count').html(grid_rows.length);
				beautify_list_view('table#report-table');
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