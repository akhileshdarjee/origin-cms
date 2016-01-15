<?php

namespace App\Http\Controllers;

use DB;
use Session;
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
			'module_icon' => 'fa-user',
			'table_name' => 'tabUser',
			'view' => 'layouts.user',
			'list_view' => '/list/user',
			'form_view' => '/form/user/',
			'link_field' => 'login_id',
			'link_field_label' => 'Login ID'
		];
	}

	public function saveForm(Request $request, $login_id = null) {
		// set the routing parameter to link field value
		$this->form_config['link_field_value'] = $login_id;
		$result = $this->before_save($request);

		if ($result && Session::get('success') == "true") {
			$result = FormController::save($request, $this->form_config);
			return $result;
		}
		else {
			return $result;
		}
	}

	public function getForm($login_id = null) {
		// set the routing parameter to link field value
		$this->form_config['link_field_value'] = $login_id;
		return FormController::show($this->form_config);
	}

	public function deleteForm($login_id, $delete_via = null) {
		// set the routing parameter to link field value
		$this->form_config['link_field_value'] = $login_id;
		return FormController::delete($this->form_config);
	}


	// define what should process before save
	public function before_save($request) {
		return $this->check_login_id($request->login_id);
	}


	// check if login id is already registered
	public function check_login_id($login_id) {
		if ($this->form_config['link_field_value']) {
			$user_login_id = DB::table('tabUser')->where('login_id', $login_id)
				->where($this->form_config['link_field'], '!=', $this->form_config['link_field_value'])->pluck('login_id');
		}
		else {
			$user_login_id = DB::table('tabUser')->where('login_id', $login_id)->pluck('login_id');
		}

		if ($user_login_id) {
			Session::put('success', 'false');
			return back()->withInput()->with(['msg' => 'Login ID: "' . $user_login_id . '" is already registered.']);
		}
		else {
			Session::put('success', 'true');
			return true;
		}
	}
}