<?php

namespace App\Http\Controllers;

use DB;
use App;
use Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AutocompleteController extends Controller
{
	public static $controllers_path = "App\\Http\\Controllers";

	public function getAutocomplete(Request $request) {

		$status_modules = ['User'];

		$module = ucwords(str_replace(" ", "", $request->get('module')));
		$module_table = $this->get_module_table($module);
		$field = $request->get('field');

		if ($request->has('fetch_fields') && $request->get('fetch_fields')) {
			$fetch_fields = $request->get('fetch_fields');
		}

		$fetch_fields = (isset($fetch_fields)) ? $fetch_fields : $field;

		$data_query = DB::table($module_table)->select($fetch_fields);

		// permission fields from perm controller
		if (Session::get('role') != 'Administrator') {
			$perm_fields = PermController::module_wise_permissions(Session::get('role'), 'Read', $module);

			if ($perm_fields) {
				foreach ($perm_fields as $field_name => $field_value) {
					if (is_array($field_value)) {
						$data_query = $data_query->whereIn($field_name, $field_value);
					}
					else {
						$data_query = $data_query->where($field_name, $field_value);
					}
				}
			}
		}

		$list_view = $this->check_list_view($request);

		// only show active data for defined tables
		if (in_array($module, $status_modules) && !$list_view) {
			$data = $data_query->where('status', 'Active')->get();
		}
		else {
			$data = $data_query->get();
		}

		return $data;
	}


	// get table name from module
	public function get_module_table($module) {
		$module_controller = App::make(self::$controllers_path . "\\" . studly_case($module) . "Controller");
		$form_config = $module_controller->form_config;

		return $form_config['table_name'];
	}


	// check is referer is list view
	public function check_list_view($request) {
		$base_url = url('/') . "/";
		$referer = $request->server('HTTP_REFERER');
		$request_path = str_replace($base_url, "", $referer);
		$request_path = explode("/", $request_path);

		if (isset($request_path[0]) && $request_path[0] === "list") {
			return true;
		}

		return false;
	}
}