<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
	public static function modules_config($role_modules = null) {
		// Modules config such as icon, color, etc
		$module_wise_config = config('modules');

		if ($role_modules) {
			$modules = array_keys($module_wise_config);
			if (is_array($modules) && is_array($role_modules)) {
				$excluded_modules = array_diff($modules, $role_modules);
			}
			else {
				abort('404');
			}

			foreach ($excluded_modules as $module) {
				unset($module_wise_config[$module]);
			}
		}

		return $module_wise_config;
	}


	// Show all modules based on user role
	public function show() {
		$user_role = Session::get('role');

		if ($user_role == 'Administrator') {
			$modules = self::modules_config();
		}
		else {
			$modules = PermController::modules_config_based_on_roles($user_role, "Read");
		}

		if ($modules) {
			return view('index', array('data' => $modules, 'file' => 'layouts.app.modules'));
		}
	}
}