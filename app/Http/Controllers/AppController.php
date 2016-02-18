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
		$app_page = SettingsController::get_app_setting('home_page');
		$app_page = 'show.app.' . $app_page;
		if (Session::has('msg') && Session::get('msg')) {
			return redirect()->route($app_page)->with('msg', Session::get('msg'));
		}
		else {
			return redirect()->route($app_page);
		}
	}
}