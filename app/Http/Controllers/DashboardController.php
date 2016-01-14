<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
	public static function show($module, $user_role) {
		$data = [];

		if ($user_role == 'Administrator') {
			$analytics = DB::table('tabCityAnalytics')
				->select('city', 'created_at')
				->get();

			return view('index', array('data' => $analytics, 'file' => 'layouts.app.'.strtolower($module)));
		}
		else {
			abort('404');
		}
	}
}