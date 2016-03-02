<?php

namespace App\Http\Controllers;

use App;
use Session;
use Exception;
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
	public function show(Request $request, $module_name = null, $id = null) {
		$this->set_form_config($module_name);
		$this->form_config['link_field_value'] = $id;
		$show_response = FormController::show($this->form_config);

		if ($request->is('api/*')) {
			// Send JSON response to API
			return $show_response;
		}
		else {
			// Returns response with view
			return $this->make_action_based_on_response($show_response);
		}
	}


	/**
	 * Stores/Saves the form value to the database
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function save(Request $request, $module_name = null, $id = null) {
		$this->set_form_config($module_name);
		$this->form_config['link_field_value'] = $id;
		$module_controller = App::make(self::$controllers_path . "\\" . studly_case($module_name) . "Controller");

		if (method_exists($module_controller, 'before_save') && is_callable(array($module_controller, 'before_save'))) {
			try {
				call_user_func(array($module_controller, 'before_save'), $request);
			}
			catch(Exception $e) {
				return back()->withInput()->with(['msg' => $e->getMessage()]);
			}
		}

		$save_response = FormController::save($request, $this->form_config);

		if ($request->is('api/*')) {
			// Send JSON response to API
			return $save_response;
		}
		else {
			// Returns response with view
			return $this->make_action_based_on_response($save_response, 'form_view');
		}
	}


	/**
	 * Deletes the form value from the database
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request, $module_name = null, $id = null) {
		$this->set_form_config($module_name);
		$this->form_config['link_field_value'] = $id;
		$module_controller = App::make(self::$controllers_path . "\\" . studly_case($module_name) . "Controller");

		if (method_exists($module_controller, 'before_delete') && is_callable(array($module_controller, 'before_delete'))) {
			try {
				call_user_func(array($module_controller, 'before_delete'), $this->form_config['link_field_value']);
			}
			catch(Exception $e) {
				return back()->withInput()->with(['msg' => $e->getMessage()]);
			}
		}

		$delete_response = FormController::delete($this->form_config);

		if ($request->is('api/*')) {
			// Send JSON response to API
			return $delete_response;
		}
		else {
			// Returns response with view
			return $this->make_action_based_on_response($delete_response, 'list_view');
		}
	}


	// get form config from specific controller
	public function set_form_config($module_name) {
		$module_controller = App::make(self::$controllers_path . "\\" . studly_case($module_name) . "Controller");
		$this->form_config = $module_controller->form_config;
	}


	// redirect to page based on api response
	public function make_action_based_on_response($response, $view_type = null) {
		$response = json_decode($response->getContent());
		$module = snake_case($this->form_config['module']);
		$data = json_decode(json_encode($response->data), true);
		$form_data = isset($data['form_data']) ? $data['form_data'] : [];

		if (isset($response->status_code) && $response->status_code == 200) {
			if ($view_type && $view_type == 'list_view') {
				return redirect()->route('show.list', array('module_name' => $module))
					->with(['msg' => $response->message]);
			}
			elseif ($view_type && $view_type == 'form_view') {
				$form_link_field_value = $form_data[$this->form_config['table_name']][$this->form_config['link_field']];

				return redirect()->route('show.doc', array('module_name' => $module, 'id' => $form_link_field_value))
					->with(['msg' => $response->message]);
			}
			else {
				return view('templates.form_view')->with($data);
			}
		}
		elseif (isset($response->status_code) && $response->status_code == 400) {
			Session::put('success', "false");
			return back()->withInput()->with(['msg' => $response->message]);
		}
		elseif (isset($response->status_code) && $response->status_code == 401) {
			Session::put('success', "false");
			if ($view_type && $view_type == 'list_view') {
				return redirect()->route('show.list', array('module_name' => $module))
					->with(['msg' => $response->message]);
			}
			elseif ($view_type && $view_type == 'form_view') {
				return back()->withInput()->with(['msg' => $response->message]);
			}
			else {
				return redirect()->route('show.app')->with('msg', $response->message);
			}
		}
		elseif (isset($response->status_code) && $response->status_code == 404) {
			if ($view_type && $view_type == 'list_view') {
				return redirect()->route('show.list', array('module_name' => $module))
					->with(['msg' => $response->message]);
			}
			elseif ($view_type && $view_type == 'form_view') {
				$form_link_field_value = $form_data[$this->form_config['table_name']][$this->form_config['link_field']];

				return redirect()->route('show.doc', array('module_name' => $module, 'id' => $form_link_field_value))
					->with(['msg' => $response->message]);
			}
			else {
				abort('404');
			}
		}
		elseif (isset($response->status_code) && $response->status_code == 500) {
			if ($view_type && $view_type == 'list_view') {
				return redirect()->route('show.list', array('module_name' => $module))
					->with(['msg' => $response->message]);
			}
			elseif ($view_type && $view_type == 'form_view') {
				if (isset($form_data[$this->form_config['table_name']]) && 
					isset($form_data[$this->form_config['table_name']][$this->form_config['link_field']])) {
						$form_link_field_value = $form_data[$this->form_config['table_name']][$this->form_config['link_field']];

						return redirect()->route('show.doc', array('module_name' => $module, 'id' => $form_link_field_value))
							->with(['msg' => $response->message]);
				}
				else {
					return back()->withInput()->with(['msg' => $response->message]);
				}
			}
		}
	}
}