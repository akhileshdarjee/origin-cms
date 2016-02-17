<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
	public static function modules_config($role_modules = null) {
		// Module UI parts such as icon, color, etc
		$module_wise_config = array(
			'mode_of_payment' => (object) array(
				'module_label' => 'Mode Of Payment', 
				'href' => '/list/mode_of_payment', 
				'icon' => 'fa fa-money', 
				'bg_color' => '#00b16a', 
				'icon_color' => '#ffffff'
			),
			'user' => (object) array(
				'module_label' => 'User', 
				'href' => '/list/user', 
				'icon' => 'fa fa-user', 
				'bg_color' => '#d35400', 
				'icon_color' => '#ffffff'
			),
		);

		if ($role_modules) {
			$modules = array_keys($module_wise_config);
			$excluded_modules = array_diff($modules, $role_modules);
			foreach ($excluded_modules as $key => $value) {
				unset($module_wise_config[$value]);
			}
		}

		return (object) $module_wise_config;
	}


	// Show all modules based on user role
	public function show() {
		$user_role = Session::get('role');

		if ($user_role == 'Administrator') {
			$modules = self::modules_config();
		}
		else {
			$modules = self::modules_config(PermController::modules_config_based_on_roles($user_role, "Read"));
		}

		if ($modules) {
			return view('index', array('data' => $modules, 'file' => 'layouts.app.modules'));
		}
	}
}