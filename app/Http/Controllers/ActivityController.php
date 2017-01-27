<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
	public function show() {
		if (Session::get('role') == "Administrator") {
			$activities = DB::table('tabActivity')
				->orderBy('id', 'desc')
				->paginate(20);

			return view('layouts.app.activities')->with(['data' => $activities]);
		}
		else {
			abort('403');
		}
	}


	// save new activity data
	public static function save($activity_data) {
		if (Session::get('login_id') || $activity_data['login_id']) {
			if ($activity_data['module'] == "Auth") {
				$activity_data['owner'] = $activity_data['last_updated_by'] = $activity_data['login_id'];
			}
			else {
				$activity_data['owner'] = $activity_data['last_updated_by'] = Session::get('login_id');
			}

			unset($activity_data['login_id']);

			$activity_data['status'] = 0;
			$activity_data['created_at'] = $activity_data['updated_at'] = date('Y-m-d H:i:s');

			DB::table('tabActivity')->insert($activity_data);
		}
	}
}