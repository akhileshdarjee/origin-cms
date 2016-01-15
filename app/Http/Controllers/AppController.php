<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
	// show home page based on app settings
	public static function show_home() {
		$redirect_to = SettingsController::get_app_setting('home_page');
		return redirect('/app/' . $redirect_to);
	}
}