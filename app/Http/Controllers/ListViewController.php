<?php

namespace App\Http\Controllers;

use DB;
use App;
use Session;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ListViewController extends Controller
{
	public static $controllers_path = "App\\Http\\Controllers";

	/**
	 * Display the specified resource in list view.
	 *
	 * @param  string  $table
	 * @return Response
	 */
	public function showList(Request $request, $module_name) {
		if ($module_name == "report") {
			return redirect()->route('show.app.reports');
		}
		else {
			$user_role = Session::get('role');

			if ($user_role == 'Administrator') {
				return $this->show_list($request, studly_case($module_name));
			}
			else {
				$allowed = PermController::role_wise_modules($user_role, "Read", studly_case($module_name));

				if ($allowed) {
					return $this->show_list($request, studly_case($module_name));
				}
				else {
					$msg = 'You are not authorized to view "'. awesome_case($module_name) . '" record(s)';

					if (url()->current() === url()->previous()) {
						return redirect()->route('show.app')->with('msg', $msg);
					}
					else {
						return back()->with(['msg' => $msg]);
					}
				}
			}
		}
	}

	public function list_view_columns($table) {
		$list_view_columns = config('list_view');
		return $list_view_columns[$table];
	}

	public function get_records($table, $search_text = null, $module_name = null) {
		$table_columns = $this->list_view_columns($table)['cols'];
		$perm_fields = [];

		if (!in_array("id", $table_columns)) {
			array_push($table_columns, "id");
		}

		$record_query = DB::table($table)->select($table_columns);

		if (Session::get('role') != 'Administrator') {
			$perm_fields = PermController::module_wise_permissions(Session::get('role'), 'Read', $module_name);

			if ($perm_fields) {
				foreach ($perm_fields as $field_name => $field_value) {
					if (is_array($field_value)) {
						$record_query = $record_query->whereIn($field_name, $field_value);
					}
					else {
						$record_query = $record_query->where($field_name, $field_value);
					}
				}
			}
		}

		// ajax search in list view
		if ($search_text) {
			$search_in = $this->list_view_columns($table)['search_via'];
			return DB::table($table)->select($table_columns)
				->where($search_in, $search_text)
				->orderBy('id', 'desc')
				->get();
		}
		else {
			$records_per_page = SettingsController::get_app_setting('list_view_records');
			return $record_query->orderBy('id', 'desc')->paginate((int) $records_per_page);
		}
	}

	public function show_list($request, $module_name) {
		$table_name = $this->get_module_table($module_name);

		try {
			$columns = $this->list_view_columns($table_name)['cols'];
		}
		catch(Exception $e) {
			return redirect()->route('show.app')->with(['msg' => awesome_case($module_name) . ' List not found']);
		}

		if ($request->ajax()) {
			// prepare rows based on search criteria
			if ($request->has('search') && $request->get('search')) {
				return $this->prepare_list_view_data($module_name, $table_name, $columns, $request->get('search'));
			}
			// delete the selected rows from the list view
			elseif ($request->has('delete_list') && !empty($request->get('delete_list'))) {
				return $this->delete_selected_records($request, $request->get('delete_list'), $module_name);
			}
			// return list of all rows for refresh list
			else {
				return $this->prepare_list_view_data($module_name, $table_name, $columns);
			}
		}
		else {
			try {
				$list_view_data = $this->prepare_list_view_data($module_name, $table_name, $columns);
			}
			catch(Exception $e) {
				return redirect()->route('show.app')->with('msg', $e->getMessage());
			}

			return view('templates.list_view', $list_view_data);
		}
	}


	// prepare list view data
	public function prepare_list_view_data($module_name, $table_name, $columns, $search_text = null) {
		try {
			$rows = $this->get_records($table_name, $search_text, $module_name);
		}
		catch(Exception $e) {
			throw new Exception('"' . $table_name . '" table not found in database');
		}

		$list_view_data = [
			'module' => $module_name,
			'rows' => $rows,
			'columns' => $columns,
			'title' => awesome_case($module_name),
			'link_field' => $this->list_view_columns($table_name)['link_field'],
			'search_via' => $this->list_view_columns($table_name)['search_via'],
			'count' => DB::table($table_name)->count()
		];

		return $list_view_data;
	}


	// filter list view data based on filter value
	public function delete_selected_records($request, $delete_records, $module_name) {
		$delete_list = [];

		foreach ($delete_records as $url) {
			$pos = strpos($url, '/', 1);
			$delete_url = substr_replace($url, "/delete", $pos, 0);
			if (!in_array($delete_url, $delete_list)) {
				array_push($delete_list, $delete_url);
			}
		}

		$action_controller = App::make(self::$controllers_path . "\\FormActions");
		foreach ($delete_list as $url) {
			$link_field_value = explode("/", $url);
			$link_field_value = end($link_field_value);
			$action_controller->delete($request, $module_name, $link_field_value);
		}

		return $delete_list;
	}


	// get table name from module
	public function get_module_table($module) {
		$module_controller = App::make(self::$controllers_path . "\\" . studly_case($module) . "Controller");
		$form_config = $module_controller->form_config;

		return $form_config['table_name'];
	}
}