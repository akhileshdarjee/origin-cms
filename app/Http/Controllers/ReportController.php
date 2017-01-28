<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{

	public static $controllers_path = "App\\Http\\Controllers";

	// Show list of all reports for the app
	public static function show() {
		if (Session::get('role') == 'Administrator') {
			$app_reports = config('reports');
			return view('layouts.app.reports')->with(['data' => $app_reports]);
		}
		else {
			return back()->withInput()->with(['msg' => 'You are not authorized to view "Reports"']);
		}
	}

	public function showReport(Request $request, $report_name) {
		if (Session::get('role') == 'Administrator') {
			$user_role = Session::get('role');
			$user_name = Session::get('user');

			$report_config = config('reports')[studly_case($report_name)];

			if (isset($report_config['allowed_roles']) && !in_array($user_role, $report_config['allowed_roles'])) {
				return redirect()->route('show.app')->with('msg', 'You are not authorized to view "' . awesome_case($report_name) . '"');
			}

			if ($request->has('download') && $request->get('download') == 'Yes') {
				$report_data = $this->get_data($request, $report_name, $user_role, $user_name);
				return $this->downloadReport(studly_case($report_name), $report_data['columns'], $report_data['rows']);
			}
			else {
				if ($request->ajax()) {
					return $this->get_data($request, $report_name, $user_role, $user_name);
				}
				else {
					$report_data = $this->get_data($request, $report_name, $user_role, $user_name);

					$report_view_data = array();

					if (isset($report_data['module']) && $report_data['module']
						&& isset($report_data['link_field']) && $report_data['link_field']
						&& isset($report_data['record_identifier']) && $report_data['record_identifier']) {
							$report_view_data = $this->prepare_report_view_data($report_name, $report_data['columns'], $report_data['rows'], $report_data['module'], $report_data['link_field'], $report_data['record_identifier']);
					}
					else {
						$report_view_data = $this->prepare_report_view_data($report_name, $report_data['columns'], $report_data['rows']);
					}

					return view('templates.report_view', $report_view_data);
				}
			}
		}
		else {
			return redirect()->route('show.app')->with('msg', 'You are not authorized to view "Reports"');
		}
	}


	public function get_data($request, $report_name, $user_role, $user_name) {
		$report_controller = App::make(self::$controllers_path . "\\Reports\\" . studly_case($report_name));
		return $report_controller->get_data($request, $user_role, $user_name);
	}


	// Returns an array of all data to be passed to report view
	public function prepare_report_view_data($report_name, $columns, $rows, $module = null, $link_field = null, $record_identifier = null) {
		$report_view_data = [
			'rows' => $rows,
			'columns' => $columns,
			'title' => awesome_case($report_name),
			'file' => 'layouts.reports.' . $report_name,
			'count' => count($rows)
		];

		if ($module && $link_field && $record_identifier) {
			$report_view_data['module'] = snake_case($module);
			$report_view_data['link_field'] = $link_field;
			$report_view_data['record_identifier'] = $record_identifier;
		}

		return $report_view_data;
	}


	// make downloadable xls file for report
	public function downloadReport($report_name, $columns, $rows, $suffix = null, $action = null, $custom_rows = null) {
		// file name for download
		if ($suffix) {
			$filename = $report_name . "-" . date('Y-m-d H:i:s') . "-" . $suffix;
		}
		else {
			$filename = $report_name . "-" . date('Y-m-d H:i:s');
		}

		// remove row property if not included in columns
		foreach($rows as $index => $row) {
			foreach ($row as $key => $value) {
				if (!in_array($key, $columns)) {
					unset($rows[$index]->$key);
				}
			}
		}

		$data_to_export['sheets'][] = [
			'header' => $columns,
			'sheet_title' => $report_name,
			'details' => $rows
		];

		$report = Excel::create($filename, function($excel) use($data_to_export, $custom_rows) {
			foreach($data_to_export['sheets'] as $data_sheet) {
				$excel->sheet($data_sheet['sheet_title'], function($sheet) use($data_sheet, $custom_rows) {
					$column_header = $data_sheet['header'];

					foreach ($column_header as $key => $value) {
						$column_header[$key] = awesome_case($column_header[$key]);
						if (strpos($column_header[$key], 'Id') !== false) {
							$column_header[$key] = str_replace("Id", "ID", $column_header[$key]);
						}
					}
					$data = [];
					array_push($data, $column_header);

					foreach($data_sheet['details'] as $excel_row) {
						array_push($data, (array) $excel_row);
					}

					// Add custom rows to file
					if ($custom_rows) {
						if (isset($custom_rows['after_line']) && $custom_rows['after_line']) {
							for ($i = 0; $i < $custom_rows['after_line']; $i++) { 
								array_push($data, []);
							}
						}

						if (isset($custom_rows['rows']) && $custom_rows['rows']) {
							foreach ($custom_rows['rows'] as $key => $value) {
								array_push($data, array($key, $value));
							}
						}
					}

					$sheet->fromArray($data, null, 'A1', false, false);
				});
			}
		});

		if ($action) {
			if ($action == "store") {
				return $report->store('xls', false, true);
			}
			else {
				$report->download('xls');
			}
		}
		else {
			$report->download('xls');
		}
	}
}