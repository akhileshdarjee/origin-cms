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
	public function showList(Request $request, $module_name)
	{
		if ($module_name == "report") {
			return redirect()->route('show.app.reports');
		}
		else {
			$user_role = $request->session()->get('role');

			if ($user_role == 'Administrator') {
				return $this->show_list($request, studly_case($module_name));
			}
			else {
				$allowed = PermController::role_wise_modules($user_role, "Read", studly_case($module_name));

				if ($allowed) {
					return $this->show_list($request, studly_case($module_name));
				}
				else {
					return back()->withInput()
						->with(['msg' => 'You are not authorized to view "'. awesome_case($module_name) . '" record(s)']);
				}
			}
		}
	}

	public function list_view_columns($table)
	{
		$list_view_columns = [
			'tabModeOfPayment' => [
				'link_field' => 'id',
				'search_via' => 'name',
				'cols' => ['name', 'status']
			],
			'tabUser' => [
				'link_field' => 'login_id',
				'search_via' => 'login_id',
				'cols' => ['login_id', 'full_name', 'role', 'status']
			],
		];

		try {
			return $list_view_columns[$table];
		}
		catch(Exception $e) {
			return redirect()->route('show.app')->with(['msg' => $e->getMessage()]);
		}
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
		$table_name = 'tab'.$module_name;
		try {
			$columns = $this->list_view_columns($table_name)['cols'];
		}
		catch(Exception $e) {
			return redirect()->route('show.app')->with(['msg' => $e->getMessage()]);
		}

		if($request->ajax()) {
			// send the autocomplete data to search box
			if ($request->get('autocomplete') == "yes") {
				$autocomplete_data = [];
				$module_link_field = $this->list_view_columns($table_name)['link_field'];
				$rows = $this->get_records($table_name);

				foreach ($rows as $key => $value) {
					array_push($autocomplete_data, $rows[$key]->$module_link_field);
				}

				return $autocomplete_data;
			}
			// prepare rows based on search criteria
			else if (!empty($request->get('search'))) {
				$search_text = $request->get('search');
				return $this->prepare_list_view_data($module_name, $table_name, $columns, $search_text);
			}
			// delete the selected rows from the list view
			elseif (!empty($request->get('delete_list'))) {
				$delete_list = [];

				foreach ($request->get('delete_list') as $url) {
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
					$action_controller->delete($module_name, $link_field_value);
				}

				return $delete_list;
			}
			// return list of all rows for refresh list
			else {
				return $this->prepare_list_view_data($module_name, $table_name, $columns);
			}
		}
		else {
			return view('templates.list_view', $this->prepare_list_view_data($module_name, $table_name, $columns));
		}
	}


	// prepare list view data
	public function prepare_list_view_data($module_name, $table_name, $columns, $search_text = null) {
		if ($search_text) {
			$rows = $this->get_records($table_name, $search_text);
		}
		else {
			$rows = $this->get_records($table_name, null, $module_name);
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
}