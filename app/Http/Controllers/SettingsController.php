<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
	public static $controllers_path = "App\\Http\\Controllers";

	// Show app settings page
	public function show() {
		if (Session::get('role') == 'Administrator') {
			$data['tabSettings'] = self::get_app_setting();
			$settings_data = [
				'form_data' => isset($data) ? $data : [],
				'title' => 'Settings',
				'icon' => 'fa fa-cogs',
				'file' => 'layouts.app.settings',
				'module' => 'Settings',
				'module_type' => 'Single'
			];

			return view('templates.form_view', $settings_data);
		}
		else {
			abort('403');
		}
	}


	// Save app settings
	public function save(Request $request) {
		if (Session::get('role') == 'Administrator') {
			$settings_data = $request->all();
			unset($settings_data["_token"]);

			if (isset($settings_data['social_login']) && $settings_data['social_login'] == "Inactive") {
				$settings_data['facebook_login'] = $settings_data['google_login'] = "Inactive";
			}

			foreach ($settings_data as $setting => $value) {
				$result = DB::table('tabSettings')
					->where('field_name', $setting)
					->update([
						'field_value' => $value, 
						'updated_at' => date('Y-m-d H:i:s'), 
						'last_updated_by' => Session::get('login_id')
					]);
			}

			// putting new app settings in session
			$auth_controller = App::make(self::$controllers_path . "\\Auth\\AuthController");
			$auth_controller->put_app_settings_in_session();

			return redirect()->route('show.app.settings')
				->with(['msg' => 'App settings successfully saved']);
		}
		else {
			abort('403');
		}
	}


	// get app setting value
	public static function get_app_setting($name = null) {
		if ($name) {
			return Session::get('app_settings')[$name];
		}
		else {
			return Session::get('app_settings');
		}
	}
}