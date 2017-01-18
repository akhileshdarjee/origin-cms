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
		$data = DB::table('tabActivity')
			->select('icon', 'form_id', 'module', 'action', 'description', 'created_at')
			->orderBy('id', 'desc')
			->paginate(20);

		return view('index', array('data' => $data, 'file' => 'layouts.app.activities'));
	}


	// save new activity data
	public static function save($activity_data) {
		$activity_data['description'] = self::makeDescription($activity_data);

		if ($activity_data['description']) {
			if ($activity_data['module'] == "Auth") {
				$activity_data['owner'] = $activity_data['last_updated_by'] = $activity_data['login_id'];
			}
			else {
				$activity_data['owner'] = $activity_data['last_updated_by'] = Session::get('login_id');
			}
		}

		if (isset($activity_data['login_id'])) {
			unset($activity_data['login_id']);
		}
		if (isset($activity_data['record_identifier'])) {
			unset($activity_data['record_identifier']);
		}

		$activity_data['status'] = 0;
		$activity_data['created_at'] = $activity_data['updated_at'] = date('Y-m-d H:i:s');

		DB::table('tabActivity')->insert($activity_data);
	}


	// get roles for whom activities of modules needs to be shown
	public function activityRoles($module) {
		$module_activities = [
			'Order' => ['Administator', 'Seller'],
			'Buyer' => ['Seller'],
			'Product' => ['Seller'],
			'Recipe' => ['Seller'],
			'Coupon' => ['Seller']
		];

		if ($module) {
			if (isset($module_activities[$module])) {
				return $module_activities[$module];
			}
			else {
				return false;
			}
		}
		else {
			return $module_activities;
		}
	}


	// make activity description based on activity data
	public static function makeDescription($activity_data) {
		$desc = false;

		if (isset($activity_data['module']) && isset($activity_data['user']) && isset($activity_data['user_id']) 
			&& isset($activity_data['action'])) {

			$user = '<a class="text-primary" href="/form/user/' . $activity_data['user_id'] . '" target="_blank">';
			$user .= '<strong>' . $activity_data["user"] . '</strong></a>';

			if ($activity_data['module'] == "Auth") {
				if ($activity_data['action'] == "Login") {
					$desc = $user . " logged in";
				}
				else {
					$desc = $user . " logged out";
				}
			}
			else {
				if (isset($activity_data['form_id'])) {
					$activity_link = '<a class="text-primary" href="/form/' . snake_case($activity_data["module"]) . '/' . $activity_data["form_id"] . '" target="_blank">';
					$activity_link .= '<strong>' . $activity_data["module"] . ': ' . $activity_data["record_identifier"] . '</strong></a>';
				}

				if ($activity_data['action'] == "Create") {
					$desc = "New " . $activity_link . " created by " . $user;
				}
				elseif ($activity_data['action'] == "Update") {
					$desc = $activity_link . " updated by " . $user;
				}
				elseif ($activity_data['action'] == "Delete") {
					$desc = '<strong>' . $activity_data["module"] . ': ' . $activity_data["record_identifier"] . '</strong>';
					$desc .= ' deleted by ' . $user;
				}
			}
		}
		else {
			$desc = false;
		}

		return $desc;
	}
}