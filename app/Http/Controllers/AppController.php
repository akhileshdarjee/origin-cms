<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
	public static function show_app_page($request, $module, $user_role) {
		if ($module == "modules") {
			return self::show_app_modules($module, $user_role);
		}
		elseif ($module == "dashboard") {
			return DashboardController::show($module, $user_role);
		}
	}


	// Show all modules based on user role
	public static function show_app_modules($module, $user_role) {
		$data = ModuleController::modules_config();

		if ($data) {
			return view('index', array('data' => $data, 'file' => 'layouts.app.'.strtolower($module)));
		}
	}
}