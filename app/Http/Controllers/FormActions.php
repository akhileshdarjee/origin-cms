<?php

namespace App\Http\Controllers;

use App;
use Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FormActions extends Controller
{
	public static $controllers_path = "App\\Http\\Controllers";

	/**
	 * Display the form view
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($module_name = null, $id = null) {
		$this->set_form_config($module_name);
		$this->form_config['link_field_value'] = $id;
		$response = FormController::show($this->form_config);
		$this->make_action_based_on_response($response);
	}


	/**
	 * Stores/Saves the form value to the database
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function save(Request $request, $module_name = null, $id = null) {
		$this->set_form_config($module_name);
		$this->form_config['link_field_value'] = $id;
		return FormController::save($request, $this->form_config);
	}


	/**
	 * Deletes the form value from the database
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete($module_name = null, $id = null) {
		$this->set_form_config($module_name);
		$this->form_config['link_field_value'] = $id;
		return FormController::delete($this->form_config);
	}


	// get form config from specific controller
	public function set_form_config($module_name) {
		$module_controller = App::make(self::$controllers_path . "\\" . ucwords(camel_case($module_name)) . "Controller");
		$this->form_config = $module_controller->form_config;
	}


	// redirect to page based on api response
	public function make_action_based_on_response($response) {
		if (isset($response['status_code']) && $response['status_code'] == 401) {
			self::put_to_session('success', "false");
			return back()->withInput()->with(['msg' => $response['message']]);
		}

		if (isset($response['status_code']) && $response['status_code'] == 404) {
			abort('404');
		}
	}
}