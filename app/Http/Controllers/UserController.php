<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
	// define common variables
	public $form_config;

	public function __construct() {
		$this->form_config = [
			'module' => 'User',
			'module_label' => 'User',
			'module_icon' => 'fa fa-user',
			'table_name' => 'tabUser',
			'view' => 'layouts.user',
			'list_view' => '/list/user',
			'form_view' => '/form/user/',
			'link_field' => 'login_id',
			'link_field_label' => 'Login ID',
			'record_identifier' => 'login_id'
		];
	}


	// define what should process before save
	public function before_save($request) {
		return $this->check_login_id($request->login_id, $request->email);
	}


	// define what should happen before delete
	public function before_delete($login_id) {
		return $this->check_user_role($login_id);
	}


	// check if login id is already registered
	public function check_login_id($login_id, $email) {
		if ($login_id) {
			$user_details = DB::table('tabUser')
				->select('login_id', 'email');

			$user_details = $user_details->where(function($query) use ($login_id, $email) {
				$query->where('login_id', $login_id)
					->orWhere('email', $email);
			});

			$user_details = $user_details->first();

			if ($user_details) {
				Session::put('success', 'false');
				if ($user_details->login_id == $login_id) {
					$msg = 'Login ID: "' . $user_details->login_id . '" is already registered.';
				}
				elseif ($user_details->email == $email) {
					$msg = 'Email ID: "' . $user_details->email . '" is already registered.';
				}

				throw new Exception($msg);
			}
			else {
				Session::put('success', 'true');
				return true;
			}
		}
		else {
			throw new Exception("Login ID is not provided");
		}
	}


	// check if role is not 'Administrator'
	public function check_user_role($login_id) {
		if ($login_id) {
			$user_role = DB::table('tabUser')
				->where('login_id', $login_id)
				->pluck('role')[0];

			if ($user_role == 'Administrator') {
				Session::put('success', 'false');
				throw new Exception("You cannot delete 'Administrator'");
			}
			else {
				Session::put('success', 'true');
				return true;
			}
		}
		else {
			throw new Exception("Login ID is not provided");
		}
	}
}