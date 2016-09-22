<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use File;
use Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
	// define field names having file
	public static $file_fields = [];
	// define modules to create, update or delete user when module is saved
	public static $user_via_modules = [];
	// define modules to send email when create or update is performed
	public static $email_modules = [];
	// define modules to create slug when create or update is performed
	public static $slug_modules = [];
	// stores link field value globally across the controller
	public static $link_field_value;


	// Shows the form view for the record 
	public static function show($form_config) {
		$user_role = self::get_from_session('role');

		if ($user_role == 'Administrator') {
			return self::show_form($form_config, $user_role);
		}
		else {
			$can_read = PermController::role_wise_modules($user_role, "Read", $form_config['module']);
			if ($can_read) {
				return self::show_form($form_config, $user_role);
			}
			else {
				$message = 'You are not authorized to view "'. $form_config['module_label'] . '" record(s)';
				return self::send_response(401, $message);
			}
		}
	}


	// Shows form view
	public static function show_form($form_config, $user_role) {
		// Shows an existing record
		if ($form_config['link_field_value']) {
			$owner = self::get_from_session('login_id');
			$data[$form_config['table_name']] = DB::table($form_config['table_name'])
				->where($form_config['link_field'], $form_config['link_field_value']);

			if ($user_role != 'Administrator') {
				$role_permissions = PermController::module_wise_permissions($user_role, "Read", $form_config['module']);

				if ($role_permissions) {
					foreach ($role_permissions as $column_name => $column_value) {
						if (is_array($column_value)) {
							$data[$form_config['table_name']] = $data[$form_config['table_name']]
								->whereIn($column_name, $column_value);
						}
						else {
							$data[$form_config['table_name']] = $data[$form_config['table_name']]
								->where($column_name, $column_value);
						}
					}
				}
				else {
					return self::send_response(404, 'Page Not Found');
				}
			}

			if (isset($form_config['parent_foreign_map'])) {
				$data_query = DB::table($form_config['table_name']);
				$fetch_field = '';

				foreach ($form_config['parent_foreign_map'] as $foreign_table => $foreign_details) {
					$foreign_key = $foreign_details['foreign_key'];
					$foreign_field = $foreign_details['fetch_field'];

					if (end($form_config['parent_foreign_map']) !== $foreign_details) {
						$fetch_field .= $foreign_field . ',';
					}
					else {
						$fetch_field .= $foreign_field;
					}

					$data_query = $data_query
						->leftJoin($foreign_table, $form_config['table_name'].'.'.$foreign_key, '=', $foreign_table.'.id');
				}

				$data[$form_config['table_name']] = $data_query
					->select(DB::raw($form_config['table_name'] . '.*, ' . $fetch_field))
					->where($form_config['table_name'].'.'.$form_config['link_field'], $form_config['link_field_value'])
					->first();
			}
			else {
				$data[$form_config['table_name']] = $data[$form_config['table_name']]->first();
			}

			if ($data && $data[$form_config['table_name']]) {
				// if child tables set and found in db then attach it with data
				if (isset($form_config['child_tables']) && isset($form_config['child_foreign_key'])) {
					if (isset($form_config['child_foreign_map']) && $form_config['child_foreign_map']) {
						foreach ($form_config['child_tables'] as $child_table) {
							$child_foreign_map = $form_config['child_foreign_map'];

							if (in_array($child_table, array_keys($child_foreign_map))) {
								$data_query = DB::table($child_table);

								$foreign_table = array_keys($child_foreign_map[$child_table]);

								if (count($foreign_table) > 1) {
									$fetch_field = '';
									foreach (array_values($foreign_table) as $index => $table_name) {
										$foreign_key = $child_foreign_map[$child_table][$table_name]['foreign_key'];
										$foreign_field = $child_foreign_map[$child_table][$table_name]['fetch_field'];

										if ($index === count($foreign_table) - 1) {
											$fetch_field .= $foreign_field;
										}
										else {
											$fetch_field .= $foreign_field . ',';
										}

										$data_query = $data_query
											->leftJoin($table_name, $child_table.'.'.$foreign_key, '=', $table_name.'.id');
									}
								}
								else {
									$foreign_table = $foreign_table[0];
									$foreign_key = $child_foreign_map[$child_table][$foreign_table]['foreign_key'];
									$fetch_field = $child_foreign_map[$child_table][$foreign_table]['fetch_field'];

									$data_query = $data_query
										->leftJoin($foreign_table, $child_table.'.'.$foreign_key, '=', $foreign_table.'.id');
								}

								$data[$child_table] = $data_query
									->select(DB::raw($child_table . '.*, ' . $fetch_field))
									->where($form_config['child_foreign_key'], $form_config['link_field_value'])
									->orderBy($child_table . '.id', 'asc')
									->get();
							}
							else {
								$data[$child_table] = DB::table($child_table)
									->where($form_config['child_foreign_key'], $form_config['link_field_value'])
									->orderBy($child_table . '.id', 'asc')
									->get();
							}
						}
					}
					else {
						foreach ($form_config['child_tables'] as $child_table) {
							$data[$child_table] = DB::table($child_table)
								->where($form_config['child_foreign_key'], $form_config['link_field_value'])
								->orderBy($child_table . '.id', 'asc')
								->get();
						}
					}
				}
			}
			else {
				return self::send_response(401, 'You are not authorized to view this record');
			}
		}
		// Shows a new form
		else {
			if ($user_role != 'Administrator') {
				$can_create = PermController::role_wise_modules($user_role, "Create", $form_config['module']);

				if (!$can_create) {
					$message = 'You are not authorized to create "'. $form_config['module_label'] . '"';
					return self::send_response(401, $message);
				}
			}
		}

		$form_data = [
			'form_data' => isset($data) ? $data : [],
			'link_field' => $form_config['link_field'],
			'record_identifier' => isset($form_config['record_identifier']) ? $form_config['record_identifier'] : $form_config['link_field'],
			'title' => $form_config['module_label'],
			'icon' => $form_config['module_icon'],
			'file' => $form_config['view'],
			'module' => $form_config['module'],
			'table_name' => $form_config['table_name']
		];

		return self::send_response(200, 'Ok', $form_data);
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
				if ($allow_update) {
					$result = self::save_form($request, $form_config, "update");
				}
				else {
					self::put_to_session('success', "false");

					$message = 'You are not authorized to update "'. $form_config['module_label'] . '" record(s)';
					return self::send_response(401, $message);
				}
			}
			else {
				if ($allow_create) {
					$result = self::save_form($request, $form_config, "create", $record_exists);
				}
				else {
					self::put_to_session('success', "false");

					$message = 'You are not authorized to create "'. $form_config['module_label'] . '" record(s)';
					return self::send_response(401, $message);
				}
			}
		}

		return $result;
	}


	// Saves record in database
	public static function save_form($request, $form_config, $action, $record_exists = null) {
		// if record already exists in database while creating
		if ($action == "create" && isset($record_exists) && $record_exists) {
			self::put_to_session('success', "false");

			$message = $form_config['module_label'] . ': "' . $request->$form_config['link_field'] . '" already exist';
			return self::send_response(400, $message);
		}
		// if link field value is not matching the request link value
		elseif ($action == "update" && $request->$form_config['link_field'] != $form_config['link_field_value']) {
			self::put_to_session('success', "false");

			$message = 'You cannot change "' . $form_config['link_field_label'] . '" for ' . $form_config['module_label'];
			return self::send_response(400, $message);
		}
		else {
			$form_data = self::populate_data($request, $form_config, $action);
			$result = self::save_data_into_db($form_data, $form_config, $action);
		}

		// if data is inserted into database then only save avatar, user, etc.
		if (is_int($result) && $result) {
			self::put_to_session('success', "true");
			$form_config['link_field_value'] = self::$link_field_value;
			$data = $form_data[$form_config['table_name']];

			// save image files
			if (isset($form_config['avatar_folder']) && $form_config['avatar_folder'] && self::$file_fields) {
				foreach($request->files->all() as $field_name => $files) {
					if (is_array($files)) {
						foreach ($files as $idx => $child_details) {
							foreach ($child_details as $child_field => $file) {
								if ($file) {
									$file->move(public_path().$form_config['avatar_folder'], $form_data[$field_name][$idx][$child_field]);
								}
							}
						}
					}
					else {
						if ($files) {
							$files->move(public_path().$form_config['avatar_folder'], $data[$field_name]);
						}
					}
				}
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

			// insert link field value to form data if not found
			if (!in_array($form_config['link_field'], $form_data)) {
				$form_data[$form_config['table_name']][$form_config['link_field']] = $form_config['link_field_value'];
			}

			$form_view_data = [
				'form_data' => isset($form_data) ? $form_data : [],
				'link_field' => $form_config['link_field'],
				'record_identifier' => isset($form_config['record_identifier']) ? $form_config['record_identifier'] : $form_config['link_field'],
				'title' => $form_config['module_label'],
				'icon' => $form_config['module_icon'],
				'file' => $form_config['view'],
				'module' => $form_config['module'],
				'table_name' => $form_config['table_name']
			];

			$form_identifier = isset($form_config['record_identifier']) ? $form_data[$form_config['table_name']][$form_config['record_identifier']] : $form_config['link_field_value'];
			$message = $form_config['module_label'] . ': "' . $form_identifier . '" saved successfully';
			return self::send_response(200, $message, $form_view_data);
		}
		else {
			self::put_to_session('success', "false");
			return $result;
		}
	}


	// insert or updates records into the database
	public static function save_data_into_db($form_data, $form_config, $action) {
		// DB::enableQueryLog();
		$user_role = self::get_from_session('role');

		// save parent data and child table data if found
		foreach ($form_data as $form_table => $form_table_data) {
			if ($form_table == $form_config['table_name']) {
				// this is parent table
				if ($action == "create") {
					$can_create = true;

					if ($user_role != 'Administrator') {
						$role_permissions = PermController::module_wise_permissions($user_role, "Create", $form_config['module']);

						if ($role_permissions) {
							$unsatisfied_rule = [];
							foreach ($role_permissions as $column_name => $column_value) {
								if (is_array($column_value)) {
									if (!in_array($form_data[$form_table][$column_name], $column_value)) {
										$can_create = false;
										$unsatisfied_rule[$column_name] = $form_data[$form_table][$column_name];
										break;
									}
								}
								else {
									if ($form_data[$form_table][$column_name] !== $column_value) {
										$can_create = false;
										$unsatisfied_rule[$column_name] = $form_data[$form_table][$column_name];
										break;
									}
								}
							}
						}
						else {
							$record_identifier = isset($form_config['record_identifier']) ? $form_config['record_identifier'] : $form_config['link_field_value'];
							$message = 'You are not authorized to create "'. $record_identifier . '" record';
							return self::send_response(401, $message);
						}
					}

					if ($can_create) {
						$result = DB::table($form_table)->insertGetId($form_table_data);
						$form_config['link_field_value'] = ($form_config['link_field'] == "id") ? $result : $form_table_data[$form_config['link_field']];
					}
					else {
						list($column_name, $column_value) = array_divide($unsatisfied_rule);
						$message = 'You are not authorized to create "'. ucwords($column_value[0]) . '" ' . ucwords($column_name[0]) . '(s)';
						return self::send_response(401, $message);
					}
				}
				else {
					$can_update = true;

					if ($user_role != 'Administrator') {
						$role_permissions = PermController::module_wise_permissions($user_role, "Update", $form_config['module']);

						if ($role_permissions) {
							$unsatisfied_rule = [];
							foreach ($role_permissions as $column_name => $column_value) {
								if (is_array($column_value)) {
									if (!in_array($form_data[$form_table][$column_name], $column_value)) {
										$can_update = false;
										$unsatisfied_rule[$column_name] = $form_data[$form_table][$column_name];
										break;
									}
								}
								else {
									if ($form_data[$form_table][$column_name] !== $column_value) {
										$can_update = false;
										$unsatisfied_rule[$column_name] = $form_data[$form_table][$column_name];
										break;
									}
								}
							}
						}
						else {
							$record_identifier = isset($form_config['record_identifier']) ? $form_config['record_identifier'] : $form_config['link_field_value'];
							$message = 'You are not authorized to update "'. $record_identifier . '" record';
							return self::send_response(401, $message);
						}
					}

					if ($can_update) {
						$result = DB::table($form_table)
							->where($form_config['link_field'], $form_config['link_field_value'])
							->update($form_table_data);
					}
					else {
						list($column_name, $column_value) = array_divide($unsatisfied_rule);
						$message = 'You are not authorized to update "'. ucwords($column_name[0]) . '" as "' . ucwords($column_value[0]) . '"';
						return self::send_response(401, $message);
					}
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

							if (isset($child_record['avatar']) && !$child_record['avatar']) {
								unset($child_record['avatar']);
							}

							$result = DB::table($form_table)
								->where('id', $id)
								->update($child_record);
						}
						elseif ($child_record['action'] == "delete") {
							unset($child_record['action']);

							$result = DB::table($form_table)
								->where($form_config['child_foreign_key'], $form_config['link_field_value'])
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

				$message = 'You are not authorized to delete "'. $form_config['module_label'] . '" record(s)';
				return self::send_response(401, $message);
			}
		}
	}


	// Delete's record from database
	public static function delete_record($form_config, $email_id = null) {
		if ($form_config['link_field_value']) {
			$data = DB::table($form_config['table_name'])
				->where($form_config['link_field'], $form_config['link_field_value']);

			$user_role = self::get_from_session('role');

			if ($user_role != 'Administrator') {
				$role_permissions = PermController::module_wise_permissions($user_role, "Delete", $form_config['module']);

				if ($role_permissions) {
					foreach ($role_permissions as $column_name => $column_value) {
						if (is_array($column_value)) {
							$data[$form_config['table_name']] = $data[$form_config['table_name']]
								->whereIn($column_name, $column_value);
						}
						else {
							$data[$form_config['table_name']] = $data[$form_config['table_name']]
								->where($column_name, $column_value);
						}
					}
				}
				else {
					$record_identifier = isset($form_config['record_identifier']) ? $form_config['record_identifier'] : $form_config['link_field_value'];
					$message = 'You are not authorized to delete "'. $record_identifier . '" record';
					return self::send_response(401, $message);
				}
			}

			$data = $data->first();

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

					$message = $form_config['module_label'] . ': "' . $form_config['link_field_value'] . '" deleted successfully';
					return self::send_response(200, $message);
				}
				else {
					self::put_to_session('success', "false");
					return self::send_response(500, 'Oops! Some problem occured while deleting. Please try again');
				}

				// deletes the avatar file if any
				if (isset($data->avatar) && $data->avatar) {
					File::delete(public_path().$data->avatar);
				}
			}
			else {
				self::put_to_session('success', "false");
				return self::send_response(404, 'No record(s) found with the given data');
			}
		}
		else {
			self::put_to_session('success', "false");

			$message = 'Cannot delete the record. "' . $form_config['link_field'] . '" is not set';
			return self::send_response(400, $message);
		}
	}


	// Returns the array of data from request with some common data
	public static function populate_data($request, $form_config, $action = null) {

		$form_data = $request->all();
		unset($form_data["_token"]);

		foreach($request->files->all() as $field_name => $files) {
			if (is_array($files)) {
				foreach ($files as $idx => $child_details) {
					foreach ($child_details as $child_field => $file) {
						if ($file) {
							self::$file_fields[$field_name][$idx] = $child_field;
						}
					}
				}
			}
			else {
				array_push(self::$file_fields, $field_name);
			}
		}

		$file_counter = 0;

		foreach (self::$file_fields as $index => $field) {
			if (is_string($field)) {
				if (isset($form_data[$field]) && $form_data[$field]) {
					if (isset($form_config['avatar_folder']) && $form_config['avatar_folder']) {
						$form_data[$field] = self::create_avatar_path($request->file($field), $form_config['avatar_folder'], $file_counter);
					}
					else {
						if (isset($_FILES[$field])) {
							$form_data[$field] = self::create_avatar_path($_FILES[$field], $form_config['avatar_folder'], $file_counter);
						}
					}
				}
			}
			elseif (is_array($field)) {
				foreach ($field as $idx => $child_field_name) {
					if (isset($form_config['avatar_folder']) && $form_config['avatar_folder']) {
						$form_data[$index][$idx][$child_field_name] = self::create_avatar_path($request->file($index)[$idx][$child_field_name], $form_config['avatar_folder'], $file_counter);
						$file_counter++;
					}
				}
			}

			$file_counter++;
		}

		// get the table schema
		$table_schema = self::get_table_schema($form_config['table_name']);

		foreach ($form_data as $column => $value) {
			if (isset($table_schema[$column]) && $table_schema[$column] == "date" && $value) {
				$value = date('Y-m-d', strtotime($value));
			}
			elseif (isset($table_schema[$column]) && $table_schema[$column] == "datetime" && $value) {
				$value = date('Y-m-d H:i:s', strtotime($value));
			}
			elseif (isset($table_schema[$column]) && $table_schema[$column] == "time" && $value) {
				$value = date('H:i:s', strtotime($value));
			}
			// checking is array is important to eliminate convert type for child tables
			elseif (!is_array($value) && $value && isset($table_schema[$column])) {
				self::convert_type($value, $table_schema[$column]);
			}

			if ($value) {
				if (isset($form_config['child_tables']) && in_array($column, $form_config['child_tables'])) {
					$data[$column] = $value;
				}
				elseif (isset($table_schema[$column])) {
 					$data[$form_config['table_name']][$column] = $value;
 				}
			}
			else {
				if ($form_config['link_field_value'] && isset($table_schema[$column])) {
					$data[$form_config['table_name']][$column] = null;
				}
			}
		}


		$data = self::merge_common_data($data, $form_config, $action);
		// web_dump($data);
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
				if (in_array($form_config['module'], self::$slug_modules) && isset($form_config['slug_source']) && $form_config['slug_source']) {
					$parent_field_name = 'slug';

					// check if generated no is already present in record
					$valid_slug = false;
					do {
						$generated_slug = str_slug($data[$table][$form_config['slug_source']], "-");

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
				// get the table schema
				$table_schema = self::get_table_schema($table);

				foreach (array_values($table_data) as $index => $child_record) {
					if ($child_record['action'] == "none") {
						unset($data[$table][$index]);
					}
					else {
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

						// remove invalid columns from child table data
						$child_columns = array_keys($table_schema);
						// provide ignored fields
						array_push($child_columns, 'action');

						foreach ($child_record as $column_name => $column_value) {
							if (!in_array($column_name, $child_columns)) {
								unset($data[$table][$index][$column_name]);
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
			if (isset($request->full_name)) {
				$full_name = $request->full_name;
			}
			elseif (isset($request->name)) {
				$full_name = $request->name;
			}

			if (isset($request->email_id)) {
				$email_id = $request->email_id;
			}
			elseif (isset($request->email)) {
				$email_id = $request->email;
			}

			$user_data = array(
				"full_name" => $full_name,
				"login_id" => $email_id,
				"email" => $email_id,
				"status" => ($module == "Guest") ? "Inactive" : $request->status,
				"last_updated_by" => self::get_from_session('login_id'), 
				"updated_at" => date('Y-m-d H:i:s')
			);

			if (isset($user_avatar) && $user_avatar) {
				$user_data["avatar"] = $user_avatar;
			}

			if ($action == "create") {
				$password = generate_password(10);
				$user_data["password"] = bcrypt($password);
				$user_data["role"] = $module;
				$user_data["owner"] = self::get_from_session('login_id');
				$user_data["created_at"] = date('Y-m-d H:i:s');

				$result = $user->insert($user_data);
				$user_data['generated_password'] = $password;
				// send password to user via email
				if (SettingsController::get_app_setting('email') == "Active") {
					EmailController::send(null, $request->email_id, "Account Registration", $user_data, $module);
				}
			}
			elseif ($action == "update") {
				$result = $user->where('login_id', $request->email_id)->update($user_data);
			}
		}

		return $result;
	}


	// creates avatar name
	public static function create_avatar_path($avatar_file, $avatar_folder, $index) {
		/* custom avatar file name */
		if ($index) {
			$avatar_name = date('YmdHis')."(" . $index . ").".$avatar_file->getClientOriginalExtension();
		}
		else {
			$avatar_name = date('YmdHis').".".$avatar_file->getClientOriginalExtension();
		}
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


	// send json response based on http status code
	public static function send_response($status_code, $message, $data = null) {
		$http_status = [
			200 => 'OK',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			404 => 'Not Found',
			500 => 'Internal Server Error'
		];

		$response_data = [
			'status' => $http_status[$status_code],
			'status_code' => $status_code,
			'message' => $message,
			'data' => $data ? $data : []
		];

		return response()->json($response_data, $status_code);
	}
}