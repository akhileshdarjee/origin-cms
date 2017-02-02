<?php

namespace App\Http\Controllers;

use DB;
use File;
use App;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
	// define common variables
	public $form_config;
	public static $controllers_path = "App\\Http\\Controllers";

	public function __construct() {
		$this->form_config = [
			'module' => 'Reports',
			'module_label' => 'Reports',
			'module_icon' => 'fa fa-sitemap',
			'table_name' => 'tabReports',
			'view' => 'layouts.reports',
			'link_field' => 'id',
			'link_field_label' => 'ID',
			'record_identifier' => 'name'
		];
	}


	// put all functions to be performed after save
	public function after_save($data) {
		$report_controller = str_replace(" ", "", $data['tabReports']['name']);
		$controllers_path = app_path('Http/Controllers/Reports/' . $report_controller . '.php');

		// save query
		if ($data['tabReports']['type'] == "Query") {
			$report_data = [
				'name' => $data['tabReports']['name'],
				'query' => $data['tabReports']['query'],
				'module' => $data['tabReports']['module'],
				'columns' => $data['tabReports']['columns']
			];

			$report_file_text = $this->get_report_file($report_data);
			File::put($controllers_path, $report_file_text);
		}
		else {
			if (File::exists($controllers_path)) {
				File::delete($controllers_path);
			}
		}

		system('composer dump-autoload');
	}


	// get report file text
	public function get_report_file($report_data) {
		$module_ctrl = App::make("App\\Http\\Controllers\\" . $report_data['module'] . "Controller");
		$module_config = $module_ctrl->form_config;

		if ($report_data['columns']) {
			$report_data['columns'] = str_replace(' ', '', $report_data['columns']);
		}

		$report_file = "<?php\r\r";
		$report_file .= "namespace App\Http\Controllers\Reports;\r\r";
		$report_file .= "use DB;\r";
		$report_file .= "use Session;\r";
		$report_file .= "use Illuminate\Http\Request;\r\r";
		$report_file .= "use App\Http\Requests;\r";
		$report_file .= "use App\Http\Controllers\Controller;\r\r";
		$report_file .= "class " . str_replace(' ', '', $report_data['name']) . " extends Controller\r";
		$report_file .= "{\r";
		$report_file .= "\t// get all rows & colummns for report\r";
		$report_file .= "\t" . 'public function get_data($request) {'. "\r";
		$report_file .= $report_data['query'] . "\r\r";
		$report_file .= "\t\treturn array(\r";
		$report_file .= "\t\t\t" . "'rows' => " . '$rows,' . "\r";
		$report_file .= "\t\t\t" . "'columns' => ['" . str_replace(",", "', '", $report_data['columns']) . "']," . "\r";
		$report_file .= "\t\t\t" . "'module' => '" . $report_data['module'] . "'," . "\r";
		$report_file .= "\t\t\t" . "'link_field' => '" . $module_config['link_field'] . "'," . "\r";
		$report_file .= "\t\t\t" . "'record_identifier' => '" . $module_config['record_identifier'] . "'" . "\r";
		$report_file .= "\t\t);\r";
		$report_file .= "\t}\r";
		$report_file .= "}";

		return $report_file;
	}


	// Show list of all reports for the app
	public static function show() {
		$reports = DB::table('tabReports')
			->select('name', 'type', 'icon', 'icon_color', 'bg_color', 'description', 'allowed_roles')
			->where('status', 'Active')
			->orderBy('sequence_no', 'asc')
			->get();

		if ($reports) {
			if (Session::get('role') == "Administrator") {
				return view('layouts.app.reports')->with(['data' => $reports]);
			}
			else {
				$app_reports = [];

				foreach ($reports as $report) {
					$allowed_roles = explode(",", $report->allowed_roles);
					$allowed_roles = array_map('trim', $allowed_roles);

					if (in_array(Session::get('role'), $allowed_roles)) {
						array_push($app_reports, $report);
					}
				}

				return view('layouts.app.reports')->with(['data' => $app_reports]);
			}
		}
		else {
			return redirect()->route('show.app')->with(['msg' => 'No Reports found']);
		}

		return back()->withInput()->with(['msg' => 'You are not authorized to view "Reports"']);
	}

	public function showReport(Request $request, $report_name) {
		if (Session::get('role') == 'Administrator') {
			$user_role = Session::get('role');
			$user_name = Session::get('user');

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

					// if report not found
					if (!isset($report_data['rows'])) {
						return $report_data;
					}

					$report_view_data = array();

					if (isset($report_data['module']) && $report_data['module']
						&& isset($report_data['link_field']) && $report_data['link_field']
						&& isset($report_data['record_identifier']) && $report_data['record_identifier']) {
							$report_view_data = $this->prepare_report_view_data($report_name, $report_data['columns'], $report_data['rows'], $report_data['module'], $report_data['link_field'], $report_data['record_identifier'], $report_data['filters']);
					}
					else {
						$report_view_data = $this->prepare_report_view_data($report_name, $report_data['columns'], $report_data['rows'], null, null, null, $report_data['filters']);
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
		if ($request->is("*/query_report/*")) {
			// query report
			$report_controller = App::make(self::$controllers_path . "\\Reports\\" . studly_case($report_name));
			$report_data = $report_controller->get_data($request, $user_role, $user_name);

			$filters = DB::table('tabReports')
				->where('name', awesome_case($report_name))
				->pluck('filters');

			$filters = is_array($filters) ? $filters[0] : $filters;
			$report_data['filters'] = $filters;

			return $report_data;
		}
		else {
			// standard report
			return $this->get_standard_report_data($request, $report_name);
		}
	}


	// Returns an array of all data to be passed to report view
	public function prepare_report_view_data($report_name, $columns, $rows, $module = null, $link_field = null, $record_identifier = null, $filters = null) {
		$report_view_data = [
			'rows' => $rows,
			'columns' => $columns,
			'filters' => $filters,
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


	public function get_standard_report_data($request, $report_name) {
		$report = DB::table('tabReports')
			->select('filters', 'module', 'columns', 'order_by')
			->where('name', awesome_case($report_name))
			->where('type', 'Standard')
			->first();

		if ($report) {
			$module_ctrl = App::make(self::$controllers_path . "\\" . $report->module . "Controller");
			$module_config = $module_ctrl->form_config;

			$query = DB::table($module_config['table_name']);

			if ($report->columns) {
				$columns = $report->columns = array_map('trim', explode(",", $report->columns));

				if (!in_array('id', $columns)) {
					array_unshift($columns, "id");
				}

				$query = $query->select($columns);
			}
			else {
				$report->columns = array_keys(FormController::get_table_schema($module_config['table_name']));
			}

			if ($request->has('filters') && $request->get('filters')) {
				$filters = $request->get('filters');
				foreach ($filters as $key => $value) {
					if (!in_array($key, ['from_date', 'to_date'])) {
						$query = $query->where($key, $filters[$key]);
					}
				}

				if (isset($filters['from_date']) && isset($filters['to_date']) && $filters['from_date'] && $filters['to_date']) {
					$query = $query->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($filters['from_date'])))
						->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($filters['to_date'])));
				}
			}

			if ($report->order_by) {
				$rows = $query->orderBy($report->order_by)->get();
			}
			else {
				$rows = $query->orderBy('id', 'desc')->get();
			}

			return array(
				'rows' => $rows,
				'columns' => $report->columns,
				'filters' => $report->filters,
				'module' => $report->module,
				'link_field' => $module_config['link_field'],
				'record_identifier' => $module_config['record_identifier']
			);
		}
		else {
			return redirect()->route('show.app')->with('msg', 'No such Report found');
		}
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