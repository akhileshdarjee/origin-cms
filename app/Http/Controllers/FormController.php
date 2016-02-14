<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use File;
use Session;
use Response;
use Mail;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
	// define modules to create, update or delete user when module is saved
	public static $user_via_modules = [];
	public static $email_modules = [];
	public static $slug_modules = [];
	public static $link_field_value;


	// Shows the form view for the record 
	public static function show($form_config) {
		$user_role = self::get_from_session('role');

		if ($user_role == 'Administrator') {
			return self::show_form($form_config);
		}
		else {
			$allowed = PermController::role_wise_modules($user_role, "Read", $form_config['module']);
			if ($allowed) {
				return self::show_form($form_config);
			}
			else {
				$response_data = [
					'status' => 'Unauthorized',
					'status_code' => 401,
					'message' => 'You are not authorized to view "'. $form_config['module_label'] . '" record(s)',
					'data' => []
				];

				return Response::json($response_data);
			}
		}
	}


	// Shows form view
	public static function show_form($form_config) {
		// Shows an existing record
		if ($form_config['link_field_value']) {
			$owner = self::get_from_session('login_id');
			$data[$form_config['table_name']] = DB::table($form_config['table_name'])
				->where($form_config['link_field'], $form_config['link_field_value'])
				->first();

			if ($data && $data[$form_config['table_name']]) {
				// if child tables set and found in db then attach it with data
				if(isset($form_config['child_tables']) && isset($form_config['child_foreign_key'])) {
					foreach ($form_config['child_tables'] as $child_table) {
						$data[$child_table] = DB::table($child_table)
							->where($form_config['child_foreign_key'], $form_config['link_field_value'])
							->get();
					}
				}
			}
			else {
				$response_data = [
					'status' => 'Not Found',
					'status_code' => 404,
					'message' => 'Page not found',
					'data' => []
				];

				return Response::json($response_data);
			}
		}
		// Shows a new form
		else {
			$user_role = self::get_from_session('role');
		}

		$form_data = [
			'form_data' => isset($data) ? $data : [],
			'link_field' => $form_config['link_field'],
			'record_identifier' => isset($form_config['record_identifier']) ? $form_config['record_identifier'] : $form_config['link_field'],
			'title' => $form_config['module_label'],
			'icon' => $form_config['module_icon'],
			'file' => $form_config['view'],
			'module' => $form_config['module']
		];

		$response_data = [
			'status' => 'OK',
			'status_code' => 200,
			'message' => 'Ok',
			'data' => $form_data
		];

		return Response::json($response_data);
	}


	// Saves or Updates the record to the database
	public static function save($request, $form_config) {
		$user_role = self::get_from_session('role');
		$record_exists = self::check_existing($request, $form_config);

		if ($user_role == 'Administrator') {
			// Updates an existing database
			if ($form_config['link_field_value'] && $record_exists) {
				$result = self::save_form($request, $form_config, "update");
			}
			// Inserts a new record to the database
			else {
				$result = self::save_form($request, $form_config, "create", $record_exists);
			}
		}
		else {
			$allow_create = PermController::role_wise_modules($user_role, "Create", $form_config['module']);
			$allow_update = PermController::role_wise_modules($user_role, "Update", $form_config['module']);

			if ($form_config['link_field_value']) {
				if ($allow_update && $record_exists) {
					$result = self::save_form($request, $form_config, "update");
				}
				else {
					self::put_to_session('success', "false");
					$response_data = [
						'status' => 'Unauthorized',
						'status_code' => 401,
						'message' => 'You are not authorized to update "'. $form_config['module_label'] . '" record(s)',
						'data' => []
					];

					return Response::json($response_data);
				}
			}
			else {
				if ($allow_create) {
					$result = self::save_form($request, $form_config, "create", $record_exists);
				}
				else {
					self::put_to_session('success', "false");
					$response_data = [
						'status' => 'Unauthorized',
						'status_code' => 401,
						'message' => 'You are not authorized to create "'. $form_config['module_label'] . '" record(s)',
						'data' => []
					];

					return Response::json($response_data);
				}
			}
		}

		if ($result && self::get_from_session('success') == "true") {
			$form_config['link_field_value'] = self::$link_field_value;

			$form_data = [
				'form_data' => isset($data) ? $data : [],
				'link_field' => $form_config['link_field'],
				'record_identifier' => isset($form_config['record_identifier']) ? $form_config['record_identifier'] : $form_config['link_field'],
				'title' => $form_config['module_label'],
				'icon' => $form_config['module_icon'],
				'file' => $form_config['view'],
				'module' => $form_config['module']
			];

			$response_data = [
				'status' => 'OK',
				'status_code' => 200,
				'message' => 'Ok',
				'data' => $form_data
			];

			return Response::json($response_data);
			return redirect($form_config['form_view'].$form_config['link_field_value'])
				->with(['msg' => $form_config['module_label'] . ': "' . $form_config['link_field_value'] . '" saved successfully']);
		}
		else {
			return $result;
		}
	}


	// Saves record in database
	public static function save_form($request, $form_config, $action, $record_exists = null) {
		// if record already exists in database while creating
		if ($action == "create" && isset($record_exists) && $record_exists) {
			self::put_to_session('success', "false");
			return redirect($form_config['form_view'])
				->with(['msg' => $form_config['module_label'] . ': "' . $request->$form_config['link_field'] . '" already exist']);
		}
		// if link field value is not matching the request link value
		elseif ($action == "update" && $request->$form_config['link_field'] != $form_config['link_field_value']) {
			self::put_to_session('success', "false");
			return redirect($form_config['form_view'].$form_config['link_field_value'])
				->with(['msg' => 'You cannot change "' . $form_config['link_field_label'] . '" for ' . $form_config['module_label']]);
		}
		else {
			$form_data = self::populate_data($request, $form_config, $action);
			$result = self::save_data_into_db($form_data, $form_config, $action);
		}

		// if data is inserted into database then only save avatar, user, etc.
		if ($result) {
			self::put_to_session('success', "true");

			$data = $form_data[$form_config['table_name']];
			if (isset($data['avatar']) && $data['avatar']) {
				$avatar = $request->file('avatar');
				$folder_path = $form_config['avatar_folder'] ? $form_config['avatar_folder'] : '/images';

				$avatar->move(public_path().$folder_path, $data['avatar']);
			}

			// create user if modules come under user_via_modules
			if (in_array($form_config['module'], self::$user_via_modules) && $result) {
				self::user_form_action($request, $form_config['module'], $action, isset($data['avatar']) ? $data['avatar'] : "");
			}

			// send email if come in email modules
			if (in_array($form_config['module'], self::$email_modules) && $result) {
				if (SettingsController::get_app_setting('email') == "Active") {
					EmailController::send(null, $data['guest_id'], null, $data, $form_config['module']);
				}
			}

			return $result;
		}
		else {
			self::put_to_session('success', "false");
			$response_data = [
				'status' => 'Internal Server Error',
				'status_code' => 500,
				'message' => 'Oops! Some problem occured while deleting. Please try again',
				'data' => []
			];

			return Response::json($response_data);
			return redirect($form_config['form_view'].$form_config['link_field_value'])
				->with(['msg' => 'Oops! Some problem occured. Please try again']);
		}
	}


	// insert or updates records into the database
	public static function save_data_into_db($form_data, $form_config, $action) {
		DB::enableQueryLog();
		// save parent data and child table data if found
		foreach ($form_data as $form_table => $form_table_data) {

			if ($form_table == $form_config['table_name']) {
				// this is parent table
				if ($action == "create") {
					$result = DB::table($form_table)->insertGetId($form_table_data);
					self::put_to_session("created_id", $result);
					$form_config['link_field_value'] = ($form_config['link_field'] == "id") ? $result : $form_table_data[$form_config['link_field']];
				}
				else {
					$result = DB::table($form_table)->where($form_config['link_field'], $form_config['link_field_value'])
						->update($form_table_data);
				}

				self::$link_field_value = $form_config['link_field_value'];
			}
			else {
				foreach ($form_table_data as $child_record) {
					if ($action == "create") {
						unset($child_record['action']);
						if (!isset($child_record[$form_config['child_foreign_key']])) {
							$child_record[$form_config['child_foreign_key']] = $form_config['link_field_value'];
						}
						$result = DB::table($form_table)->insert($child_record);
					}
					else {
						if ($child_record['action'] == "create") {
							unset($child_record['action']);
							$child_record['owner'] = self::get_from_session('login_id');
							$child_record['created_at'] = date('Y-m-d H:i:s');

							$result = DB::table($form_table)->insert($child_record);
						}
						elseif ($child_record['action'] == "update") {
							unset($child_record['action']);
							$id = $child_record['id'];
							unset($child_record['id']);

							$result = DB::table($form_table)->where('id', $id)->update($child_record);
						}
						elseif ($child_record['action'] == "delete") {
							unset($child_record['action']);

							$result = DB::table($form_table)->where($form_config['child_foreign_key'], $form_config['link_field_value'])
								->where('id', $child_record['id'])->delete();
						}
					}
				}
			}
		}

		return $result;
	}


	// Delete the record from the database
	public static function delete($form_config, $email_id = null) {
		$user_role = self::get_from_session('role');

		if ($user_role == 'Administrator') {
			return self::delete_record($form_config, $email_id);
		}
		else {
			$allowed = PermController::role_wise_modules($user_role, "Delete", $form_config['module']);
			if ($allowed) {
				return self::delete_record($form_config, $email_id);
			}
			else {
				self::put_to_session('success', "false");
				$response_data = [
					'status' => 'Unauthorized',
					'status_code' => 401,
					'message' => 'You are not authorized to delete "'. $form_config['module_label'] . '" record(s)',
					'data' => []
				];

				return Response::json($response_data);
			}
		}
	}


	// Delete's record from database
	public static function delete_record($form_config, $email_id = null) {
		if ($form_config['link_field_value']) {
			$data = DB::table($form_config['table_name'])
				->where($form_config['link_field'], $form_config['link_field_value'])
				->first();

			if ($data) {
				// if record found then only delete it
				$result = DB::table($form_config['table_name'])
					->where($form_config['link_field'], $form_config['link_field_value'])
					->delete();

				if ($result) {
					// delete child tables if found
					if (isset($form_config['child_tables']) && isset($form_config['child_foreign_key'])) {
						foreach ($form_config['child_tables'] as $child_table) {
							DB::table($child_table)
								->where($form_config['child_foreign_key'], $form_config['link_field_value'])
								->delete();
						}
					}

					// delete user if modules come under user_via_modules
					if (in_array($form_config['module'], self::$user_via_modules)) {
						self::user_form_action($email_id, $form_config['module'], "delete");
					}

					self::put_to_session('success', "true");
					$response_data = [
						'status' => 'OK',
						'status_code' => 200,
						'message' => $form_config['module_label'] . ': "' . $form_config['link_field_value'] . '" deleted successfully',
						'data' => []
					];

					return Response::json($response_data);
				}
				else {
					self::put_to_session('success', "false");
					$response_data = [
						'status' => 'Internal Server Error',
						'status_code' => 500,
						'message' => 'Oops! Some problem occured while deleting. Please try again',
						'data' => []
					];

					return Response::json($response_data);
				}

				// deletes the avatar file if any
				if (isset($data->avatar) && $data->avatar) {
					File::delete(public_path().$data->avatar);
				}
			}
			else {
				self::put_to_session('success', "false");
				$response_data = [
					'status' => 'Not Found',
					'status_code' => 404,
					'message' => 'No record(s) found with the given data',
					'data' => []
				];

				return Response::json($response_data);
			}
		}
		else {
			self::put_to_session('success', "false");
			$response_data = [
				'status' => 'Bad Request',
				'status_code' => 400,
				'message' => 'Cannot delete the record. "' . $form_config['link_field'] . '" is not set',
				'data' => []
			];

			return Response::json($response_data);
		}
	}


	// Returns the array of data from request with some common data
	public static function populate_data($request, $form_config, $action = null) {

		$form_data = $request->all();
		unset($form_data["_token"]);

		if ($request->hasFile('avatar') && isset($form_config['avatar_folder']) && $form_config['avatar_folder']) {
			$form_data['avatar'] = self::create_avatar_path($request->file('avatar'), $form_config['avatar_folder']);
		}

		// get the table schema
		$table_schema = self::get_table_schema($form_config['table_name']);

		foreach ($form_data as $column => $value) {
			if (isset($table_schema[$column]) && $table_schema[$column] == "date") {
				$value = date('Y-m-d', strtotime($value));
			}
			elseif (isset($table_schema[$column]) && $table_schema[$column] == "datetime") {
				$value = date('Y-m-d H:i:s', strtotime($value));
			}
			// checking is array is important to eliminate convert type for child tables
			elseif (!is_array($value)) {
				self::convert_type($value, $table_schema[$column]);
			}

			if ($value) {
				if (isset($form_config['child_tables']) && in_array($column, $form_config['child_tables'])) {
					$data[$column] = $value;
				}
				else {
					$data[$form_config['table_name']][$column] = $value;
				}
			}
			else {
				if ($form_config['link_field_value']) {
					$data[$form_config['table_name']][$column] = null;
				}
			}
		}


		$data = self::merge_common_data($data, $form_config, $action);
		// echo json_encode($data);
		// exit();
		return $data;
	}


	// converts the type of request value to the type to be inserted in db
	public static function convert_type($value, $type_name) {
		if ($type_name == "decimal") {
			$type_name = "float";
		}
		elseif ($type_name == "text") {
			$type_name = "string";
		}

		settype($value, $type_name);
	}


	// Returns the array of data from request with some common data and child data
	public static function merge_common_data($data, $form_config, $action = null) {
		$owner = $last_updated_by = self::get_from_session('login_id');
		$created_at = $updated_at = date('Y-m-d H:i:s');

		$parent_table = $form_config['table_name'];

		foreach ($data as $table => $table_data) {
			if ($table == $parent_table) {
				$data[$table]['last_updated_by'] = $last_updated_by;
				$data[$table]['updated_at'] = $updated_at;

				if ($action == "create") {
					$data[$table]['owner'] = $owner;
					$data[$table]['created_at'] = $created_at;
				}

				// check if module come under slug modules list
				if (in_array($form_config['module'], self::$slug_modules) && $action == "create") {
					$parent_field_name = 'slug';

					// check if generated no is already present in record
					$valid_slug = false;
					do {
						$generated_slug = self::createSlug($data[$table]['title']);

						$existing_slug = DB::table($table)
							->where($parent_field_name, $generated_slug)
							->pluck($parent_field_name);

						if (!$existing_slug) {
							$valid_slug = true;
						}
					} while ($valid_slug == false);

					$data[$table][$parent_field_name] = $generated_slug;
				}
			}
			else {
				foreach (array_values($table_data) as $index => $child_record) {
					if (isset($data[$table][$index]['id']) && $data[$table][$index]['id']) {
						$data[$table][$index]['id'] = (int) $data[$table][$index]['id'];
					}
					// insert foreign key of child table which connects to parent table link field
					if (isset($data[$parent_table]) && isset($data[$parent_table][$form_config['link_field']])) {
						$data[$table][$index][$form_config['child_foreign_key']] = $data[$parent_table][$form_config['link_field']];
					}
					if (isset($form_config['copy_parent_fields']) && isset($data[$parent_table])) {
						foreach ($form_config['copy_parent_fields'] as $parent_field => $child_field) {
							$data[$table][$index][$child_field] = $data[$parent_table][$parent_field];
						}
					}

					$data[$table][$index]['last_updated_by'] = $last_updated_by;
					$data[$table][$index]['updated_at'] = $updated_at;

					if ($action == "create") {
						$data[$table][$index]['owner'] = $owner;
						$data[$table][$index]['created_at'] = $created_at;
					}
				}
			}
		}

		return $data;
	}


	// performs form actions for user table
	public static function user_form_action($request, $module, $action, $user_avatar = null) {
		$user = DB::table('tabUser');

		if ($action == "delete") {
			$result = $user->where('login_id', $request)->delete();
		}
		else {
			$user_data = array(
				"full_name" => $request->full_name,
				"login_id" => $request->email_id,
				"email" => $request->email_id,
				"status" => ($module == "Guest") ? "Inactive" : $request->status,
				"last_updated_by" => self::get_from_session('login_id'), 
				"updated_at" => date('Y-m-d H:i:s')
			);

			if (isset($user_avatar) && $user_avatar) {
				$user_data["avatar"] = $user_avatar;
			}

			if ($action == "create") {
				$password = FormController::generate_password();
				$user_data["password"] = bcrypt($password);
				$user_data["role"] = $module;
				$user_data["owner"] = self::get_from_session('login_id');
				$user_data["created_at"] = date('Y-m-d H:i:s');

				$result = $user->insert($user_data);
				$user_data['generated_password'] = $password;
				// send password to user via email
				if (SettingsController::get_app_setting('email') == "Active") {
					EmailController::send(null, $request->email_id, "Basecamp Account Password", $user_data, $module);
				}
			}
			elseif ($action == "update") {
				$result = $user->where('login_id', $request->email_id)->update($user_data);
			}
		}

		return $result;
	}


	// creates avatar name
	public static function create_avatar_path($avatar_file, $avatar_folder) {
		/* custom avatar file name */
		$avatar_name = date('YmdHis').".".$avatar_file->getClientOriginalExtension();
		/* full avatar path */
		$avatar_full_path = $avatar_folder ."/". $avatar_name;

		return $avatar_full_path;
	}


	// checks for an existing record in the database
	public static function check_existing($request, $form_config) {
		$existing_record = false;

		if ($request->$form_config['link_field']) {
			$existing_record = DB::table($form_config['table_name'])
				->where($form_config['link_field'], $request->$form_config['link_field'])
				->first();
		}

		return $existing_record ? true : false;
	}


	// returns the value from session if auth check else return to login
	public static function get_from_session($key) {
		if (Session::get('role') == "Website User") {
			return Session::get($key);
		}
		else {
			if (Auth::check() && Session::has($key) && Session::get($key)) {
				return Session::get($key);
			}
			else {
				return false;
			}
		}
	}


	// sets the value to session if auth check else return to login
	public static function put_to_session($key, $value) {
		if (Auth::check()) {
			return Session::put($key, $value);
		}
		else {
			return false;
		}
	}


	// generates a new random password
	public static function generate_password($length = null, $only_numbers = null) {
		if ($only_numbers) {
			$alphabet = "0123456789";
		}
		else {
			$alphabet = "abcdefghijklmnopqrstuwxyz_ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789@#$.";
		}

		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		$length = $length ? $length : 10;
		for ($i = 0; $i < $length; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}

		return implode($pass); //turn the array into a string
	}


	// returns table column name and column type
	public static function get_table_schema($table) {
		$columns = DB::connection()
			->getDoctrineSchemaManager()
			->listTableColumns($table);

		$table_schema = [];

		foreach($columns as $column) {
			$table_schema[$column->getName()] = $column->getType()->getName();
		}

		return $table_schema;
	}


	// create slug for given string
	public static function createSlug($str, $options = array()) {
		// Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

		$defaults = array(
			'delimiter' => '-',
			'limit' => null,
			'lowercase' => true,
			'replacements' => array(),
			'transliterate' => false,
		);

		// Merge options
		$options = array_merge($defaults, $options);

		$char_map = array(
			// Latin
			'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
			'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
			'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
			'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
			'ß' => 'ss', 
			'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
			'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
			'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
			'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
			'ÿ' => 'y',

			// Latin symbols
			'©' => '(c)',

			// Greek
			'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
			'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
			'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
			'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
			'Ϋ' => 'Y',
			'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
			'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
			'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
			'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
			'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

			// Turkish
			'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
			'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 

			// Russian
			'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
			'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
			'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
			'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
			'Я' => 'Ya',
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
			'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
			'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
			'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
			'я' => 'ya',

			// Ukrainian
			'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
			'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

			// Czech
			'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
			'Ž' => 'Z', 
			'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
			'ž' => 'z', 

			// Polish
			'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
			'Ż' => 'Z', 
			'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
			'ż' => 'z',

			// Latvian
			'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
			'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
			'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
			'š' => 's', 'ū' => 'u', 'ž' => 'z'
		);

		// Make custom replacements
		$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
		
		// Transliterate characters to ASCII
		if ($options['transliterate']) {
			$str = str_replace(array_keys($char_map), $char_map, $str);
		}

		// Replace non-alphanumeric characters with our delimiter
		$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

		// Remove duplicate delimiters
		$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

		// Truncate slug to max. characters
		$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

		// Remove delimiter from ends
		$str = trim($str, $options['delimiter']);

		return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}
}