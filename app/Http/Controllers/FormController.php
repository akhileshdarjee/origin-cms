<?php

namespace App\Http\Controllers;

use DB;
use File;
use Str;

trait FormController
{
    // define field names having file
    public $file_fields = [];
    // define modules to create, update or delete user when module is saved
    public $user_via_modules = [];
    // define modules to create slug when create or update is performed
    public $slug_modules = [];
    // stores link field value globally across the controller
    public $link_field_value;

    public function showDoc($module)
    {
        $user_role = auth()->user()->role;
        $read_allowed = true;

        if ($user_role != 'System Administrator') {
            $read_allowed = $this->roleWiseModules($user_role, "Read", $module['name']);
        }

        if ($read_allowed) {
            return $this->showForm($module, $user_role);
        } else {
            session()->flash('success', false);
            $message = __('You are not authorized to view') . ' "' . __($module['display_name']) . '" ' . __('records');

            return $this->sendResponse(401, $message);
        }
    }

    public function showForm($module, $user_role)
    {
        if ($user_role == "System Administrator") {
            $perms = ['create' => true, 'update' => true, 'delete' => true];
        } else {
            $perms = [];
            $perms['create'] = $this->roleWiseModules($user_role, "Create", $module['name']);
            $perms['update'] = $this->roleWiseModules($user_role, "Update", $module['name']);
            $perms['delete'] = $this->roleWiseModules($user_role, "Delete", $module['name']);
        }

        // Shows an existing record
        if ($module['link_field_value']) {
            $owner = auth()->user()->username;
            $record_exists = $this->checkIfExists($module);

            if (!$record_exists) {
                session()->flash('success', false);
                $message = __('No such record found');

                return $this->sendResponse(404, $message);
            }

            $data[$module['table_name']] = DB::table($module['table_name'])
                ->select($module['table_name'] . '.*');

            if ($user_role != 'System Administrator') {
                $role_permissions = $this->moduleWisePermissions($user_role, "Read", $module['name']);

                if ($role_permissions) {
                    foreach ($role_permissions as $column_name => $column_value) {
                        if (is_array($column_value)) {
                            $data[$module['table_name']] = $data[$module['table_name']]
                                ->whereIn($module['table_name'] . '.' . $column_name, $column_value);
                        } else {
                            $data[$module['table_name']] = $data[$module['table_name']]
                                ->where($module['table_name'] . '.' . $column_name, $column_value);
                        }
                    }
                } else {
                    session()->flash('success', false);
                    return $this->sendResponse(404, __('Page Not Found'));
                }
            }

            if (isset($module['parent_foreign_map'])) {
                $fetch_fields = [];

                foreach ($module['parent_foreign_map'] as $foreign_table => $foreign_details) {
                    $foreign_key = $foreign_details['foreign_key'];
                    $foreign_field = $foreign_details['fetch_field'];
                    $local_key = isset($foreign_details['local_key']) ? $foreign_details['local_key'] : 'id';
                    $foreign_field = explode(",", $foreign_field);

                    foreach ($foreign_field as $field) {
                        array_push($fetch_fields, trim($field));
                    }

                    $data[$module['table_name']] = $data[$module['table_name']]
                        ->leftJoin($foreign_table, $module['table_name'].'.'.$foreign_key, '=', $foreign_table.'.'.$local_key);
                }

                $data[$module['table_name']] = $data[$module['table_name']]
                    ->addSelect($fetch_fields);
            }

            $data[$module['table_name']] = $data[$module['table_name']]
                ->where($module['table_name'].'.'.$module['link_field'], $module['link_field_value'])
                ->first();

            if ($data && $data[$module['table_name']]) {
                // if child tables set and found in db then attach it with data
                if (isset($module['child_tables']) && isset($module['child_foreign_key'])) {
                    if (isset($module['child_foreign_map']) && $module['child_foreign_map']) {
                        foreach ($module['child_tables'] as $child_table) {
                            $child_foreign_map = $module['child_foreign_map'];

                            if (in_array($child_table, array_keys($child_foreign_map))) {
                                $data_query = DB::table($child_table);
                                $foreign_table = array_keys($child_foreign_map[$child_table]);

                                if (count($foreign_table) > 1) {
                                    $fetch_field = '';

                                    foreach (array_values($foreign_table) as $index => $table_name) {
                                        $foreign_alias = explode(' as ', $table_name);
                                        $foreign_alias = array_map('trim', $foreign_alias);
                                        $foreign_key = $child_foreign_map[$child_table][$table_name]['foreign_key'];
                                        $foreign_field = $child_foreign_map[$child_table][$table_name]['fetch_field'];

                                        if ($index === count($foreign_table) - 1) {
                                            $fetch_field .= $foreign_field;
                                        } else {
                                            $fetch_field .= $foreign_field . ',';
                                        }

                                        if (count($foreign_alias) == 2) {
                                            $data_query = $data_query
                                                ->leftJoin($table_name, $child_table.'.'.$foreign_key, '=', $foreign_alias[1].'.id');
                                        } else {
                                            $data_query = $data_query
                                                ->leftJoin($table_name, $child_table.'.'.$foreign_key, '=', $table_name.'.id');
                                        }
                                    }
                                } else {
                                    $foreign_table = $foreign_table[0];
                                    $foreign_alias = explode(' as ', $foreign_table);
                                    $foreign_alias = array_map('trim', $foreign_alias);
                                    $foreign_key = $child_foreign_map[$child_table][$foreign_table]['foreign_key'];
                                    $fetch_field = $child_foreign_map[$child_table][$foreign_table]['fetch_field'];

                                    if (count($foreign_alias) == 2) {
                                        $data_query = $data_query
                                            ->leftJoin($foreign_table, $child_table.'.'.$foreign_key, '=', $foreign_alias[1].'.id');
                                    } else {
                                        $data_query = $data_query
                                            ->leftJoin($foreign_table, $child_table.'.'.$foreign_key, '=', $foreign_table.'.id');
                                    }
                                }

                                $data[$child_table] = $data_query
                                    ->select(DB::raw($child_table . '.*, ' . $fetch_field))
                                    ->where($module['child_foreign_key'], $module['link_field_value'])
                                    ->orderBy($child_table . '.id', 'asc')
                                    ->get();
                            } else {
                                $data[$child_table] = DB::table($child_table)
                                    ->where($module['child_foreign_key'], $module['link_field_value'])
                                    ->orderBy($child_table . '.id', 'asc')
                                    ->get();
                            }
                        }
                    } else {
                        foreach ($module['child_tables'] as $child_table) {
                            $data[$child_table] = DB::table($child_table)
                                ->where($module['child_foreign_key'], $module['link_field_value'])
                                ->orderBy($child_table . '.id', 'asc')
                                ->get();
                        }
                    }
                }
            } else {
                session()->flash('success', false);
                return $this->sendResponse(401, __('You are not authorized to view this record'));
            }
        }
        // Shows a new form
        else {
            if (!$perms['create']) {
                session()->flash('success', false);
                $message = __('You are not authorized to create') . ' "' . __($module['display_name']) . '"';
                return $this->sendResponse(401, $message);
            }
        }

        $form_data = [
            'form_data' => isset($data) ? $data : [],
            'link_field' => $module['link_field'],
            'form_title' => isset($module['form_title']) ? $module['form_title'] : $module['link_field'],
            'title' => $module['display_name'],
            'icon' => $module['icon'],
            'file' => $module['view'],
            'slug' => $module['slug'],
            'module' => $module['name'],
            'table_name' => $module['table_name'],
            'permissions' => $perms
        ];

        if ($module['link_field_value']) {
            $previous = DB::table($module['table_name'])
                ->where('id', '<', $module['link_field_value'])
                ->max('id');

            $next = DB::table($module['table_name'])
                ->where('id', '>', $module['link_field_value'])
                ->min('id');

            if ($previous) {
                $previous = route('show.doc', ['slug' => $module['slug'], 'id' => $previous]);
            }

            if ($next) {
                $next = route('show.doc', ['slug' => $module['slug'], 'id' => $next]);
            }

            $form_data['previous'] = $previous;
            $form_data['next'] = $next;
        }

        return $this->sendResponse(200, __('Ok'), $form_data);
    }

    // Saves or Updates the record to the database
    public function saveDoc($request, $module)
    {
        $user_role = auth()->user()->role;
        $record_exists = $this->checkIfExists($module);

        if ($user_role == 'System Administrator') {
            if ($module['link_field_value'] && $record_exists) {
                // Updates an existing database
                $result = $this->saveForm($request, $module, "update");
            } else {
                // Inserts a new record to the database
                $result = $this->saveForm($request, $module, "create", $record_exists);
            }
        } else {
            $allow_create = $this->roleWiseModules($user_role, "Create", $module['name']);
            $allow_update = $this->roleWiseModules($user_role, "Update", $module['name']);

            if ($module['link_field_value']) {
                if ($allow_update) {
                    $result = $this->saveForm($request, $module, "update");
                } else {
                    session()->flash('success', false);

                    $message = __('You are not authorized to update') . ' "' . __($module['display_name']) . '" ' . __('records');
                    return $this->sendResponse(401, $message);
                }
            } else {
                if ($allow_create) {
                    $result = $this->saveForm($request, $module, "create", $record_exists);
                } else {
                    session()->flash('success', false);

                    $message = __('You are not authorized to create') . ' "' . __($module['display_name']) . '" ' . __('records');
                    return $this->sendResponse(401, $message);
                }
            }
        }

        return $result;
    }

    public function saveForm($request, $module, $action, $record_exists = null)
    {
        if ($action == "create" && isset($record_exists) && $record_exists) {
            // if record already exists in database while creating
            session()->flash('success', false);

            $message = __($module['display_name']) . ': "' . $request->get($module['link_field']) . '" ' . __('already exists');
            return $this->sendResponse(400, $message);
        } elseif ($action == "update" && $request->get($module['link_field']) != $module['link_field_value']) {
            // if link field value is not matching the request link value
            session()->flash('success', false);

            $message = __('You cannot change') . ' "' . __($module['link_field_label']) . '" ' . __('for') . ' ' . __($module['display_name']);
            return $this->sendResponse(400, $message);
        } else {
            $form_data = $this->populateData($request, $module, $action);
            $form_data = $this->saveDataInDb($form_data, $module, $action);
        }

        // if data is inserted into database then only save files, user, etc.
        if ($form_data && isset($form_data[$module['table_name']])) {
            session()->flash('success', true);
            $module['link_field_value'] = $this->link_field_value;
            $data = $form_data[$module['table_name']];

            // store user uploaded files
            if (isset($module['upload_folder']) && $module['upload_folder'] && $this->file_fields) {
                foreach($request->files->all() as $field_name => $files) {
                    if (is_array($files)) {
                        foreach ($files as $idx => $child_details) {
                            foreach ($child_details as $child_field => $file) {
                                if ($file && isset($form_data[$field_name][$idx][$child_field]) && $form_data[$field_name][$idx][$child_field]) {
                                    $request->file($field_name)[$idx][$child_field]->storeAs(
                                        $module['upload_folder'], 
                                        last(explode("/", $form_data[$field_name][$idx][$child_field])), 
                                        'public'
                                    );
                                }
                            }
                        }
                    } else {
                        if ($files && isset($data[$field_name]) && $data[$field_name]) {
                            $request->file($field_name)->storeAs(
                                $module['upload_folder'], 
                                last(explode("/", $data[$field_name])), 
                                'public'
                            );
                        }
                    }
                }
            }

            // update activity
            $activity_data = [
                'module' => $module['display_name'],
                'icon' => $module['icon'],
                'form_id' => $module['link_field_value']
            ];

            if (isset($module['form_title']) && $data[$module['form_title']]) {
                $activity_data['form_title'] = $data[$module['form_title']];
            } else {
                $activity_data['form_title'] = $data['id'];
            }

            $this->saveActivity($activity_data, ucwords($action));

            // create user if modules come under user_via_modules
            if (in_array($module['name'], $this->user_via_modules)) {
                $this->userFormAction($request, $module['name'], $action, isset($data['avatar']) ? $data['avatar'] : null);
            }

            $form_view_data = [
                'form_data' => isset($form_data) ? $form_data : [],
                'link_field' => $module['link_field'],
                'form_title' => isset($module['form_title']) ? $module['form_title'] : $module['link_field'],
                'title' => $module['display_name'],
                'icon' => $module['icon'],
                'file' => $module['view'],
                'slug' => $module['slug'],
                'module' => $module['name'],
                'table_name' => $module['table_name']
            ];

            $form_identifier = isset($module['form_title']) ? $form_data[$module['table_name']][$module['form_title']] : $module['link_field_value'];
            $message = __($module['display_name']) . ': "' . $form_identifier . '" ' . __('saved successfully');

            return $this->sendResponse(200, $message, $form_view_data);
        } else {
            session()->flash('success', false);
            return $form_data;
        }
    }

    // insert or updates records into the database
    public function saveDataInDb($form_data, $module, $action)
    {
        $user_role = auth()->user()->role;

        // save parent data and child table data if found
        foreach ($form_data as $form_table => $form_table_data) {
            if ($form_table == $module['table_name']) {
                // this is parent table
                if ($action == "create") {
                    $can_create = true;

                    if ($user_role != 'System Administrator') {
                        $role_permissions = $this->moduleWisePermissions($user_role, "Create", $module['name']);

                        if ($role_permissions) {
                            $unsatisfied_rule = [];

                            foreach ($role_permissions as $column_name => $column_value) {
                                if (is_array($column_value)) {
                                    if (!in_array($form_data[$form_table][$column_name], $column_value)) {
                                        $can_create = false;
                                        $unsatisfied_rule[$column_name] = $form_data[$form_table][$column_name];
                                        break;
                                    }
                                } else {
                                    if ($form_data[$form_table][$column_name] != $column_value) {
                                        $can_create = false;
                                        $unsatisfied_rule[$column_name] = $form_data[$form_table][$column_name];
                                        break;
                                    }
                                }
                            }
                        } else {
                            $form_title = isset($module['form_title']) ? $module['form_title'] : $module['link_field_value'];
                            $message = __('You are not authorized to create') . ' "' . $form_title . '" ' . __('record');

                            return $this->sendResponse(401, $message);
                        }
                    }

                    if ($can_create) {
                        $result = DB::table($form_table)->insertGetId($form_table_data);
                        $module['link_field_value'] = ($module['link_field'] == "id") ? $result : $form_table_data[$module['link_field']];

                        // insert link field value to form data if not found
                        if (!in_array($module['link_field'], $form_data)) {
                            $form_data[$module['table_name']][$module['link_field']] = $module['link_field_value'];
                        }

                        session()->flash('newly_created', true);
                    } else {
                        list($column_name, $column_value) = array_divide($unsatisfied_rule);
                        $message = __('You are not authorized to create') . ' "' . __(ucwords($column_value[0])) . '" ' . __(ucwords($column_name[0]));

                        return $this->sendResponse(401, $message);
                    }
                } else {
                    $can_update = true;

                    if ($user_role != 'System Administrator') {
                        $role_permissions = $this->moduleWisePermissions($user_role, "Update", $module['name']);

                        if ($role_permissions) {
                            $unsatisfied_rule = [];

                            $current_data = DB::table($form_table)
                                ->where($module['link_field'], $form_data[$form_table][$module['link_field']])
                                ->first();

                            foreach ($role_permissions as $column_name => $column_value) {
                                if (isset($form_data[$form_table][$column_name]) && $form_data[$form_table][$column_name]) {
                                    $data_value = $form_data[$form_table][$column_name];
                                } else {
                                    $data_value = $current_data->{$column_name};
                                }

                                if (is_array($column_value)) {
                                    if (!in_array($data_value, $column_value)) {
                                        $can_update = false;
                                        $unsatisfied_rule[$column_name] = $data_value;
                                        break;
                                    }
                                } else {
                                    if ($data_value != $column_value) {
                                        $can_update = false;
                                        $unsatisfied_rule[$column_name] = $data_value;
                                        break;
                                    }
                                }
                            }
                        } else {
                            $form_title = isset($module['form_title']) ? $module['form_title'] : $module['link_field_value'];
                            $message = __('You are not authorized to update') . ' "' . $form_title . '" ' . __('record');

                            return $this->sendResponse(401, $message);
                        }
                    }

                    if ($can_update) {
                        $result = DB::table($form_table)
                            ->where($module['link_field'], $module['link_field_value'])
                            ->update($form_table_data);
                    } else {
                        list($column_name, $column_value) = array_divide($unsatisfied_rule);
                        $message = __('You are not authorized to update') . ' "' . __(ucwords($column_name[0])) . '" ' . __('as') . ' "' . __(ucwords($column_value[0])) . '"';

                        return $this->sendResponse(401, $message);
                    }
                }

                $this->link_field_value = $module['link_field_value'];
            } else {
                foreach ($form_table_data as $idx => $child_record) {
                    if ($action == "create") {
                        unset($child_record['action']);

                        if (count($child_record)) {
                            if (!isset($child_record[$module['child_foreign_key']])) {
                                $child_record[$module['child_foreign_key']] = $module['link_field_value'];
                            }

                            $result = DB::table($form_table)->insertGetId($child_record);
                            $form_data[$form_table][$idx]['id'] = $result;
                        }
                    } else {
                        if ($child_record['action'] == "create") {
                            unset($child_record['action']);

                            if (count($child_record)) {
                                $result = DB::table($form_table)->insertGetId($child_record);
                                $form_data[$form_table][$idx]['id'] = $result;
                            }
                        } elseif ($child_record['action'] == "update") {
                            unset($child_record['action']);
                            $id = $child_record['id'];
                            unset($child_record['id']);

                            if (isset($child_record['avatar']) && !$child_record['avatar']) {
                                unset($child_record['avatar']);
                            }

                            $result = DB::table($form_table)
                                ->where('id', $id)
                                ->update($child_record);
                        } elseif ($child_record['action'] == "delete") {
                            unset($child_record['action']);

                            $result = DB::table($form_table)
                                ->where($module['child_foreign_key'], $module['link_field_value'])
                                ->where('id', $child_record['id'])->delete();
                        }
                    }
                }
            }
        }

        if ($result) {
            return $form_data;
        } else {
            return false;
        }
    }

    // Delete the record from the database
    public function deleteDoc($request, $module, $email = null)
    {
        $user_role = auth()->user()->role;
        $delete_allowed = true;

        if ($user_role != 'System Administrator') {
            $delete_allowed = $this->roleWiseModules($user_role, "Delete", $module['name']);
        }

        if ($delete_allowed) {
            return $this->deleteRecord($request, $module, $email);
        } else {
            session()->flash('success', false);
            $message = __('You are not authorized to delete') . ' "' . __($module['display_name']) . '" ' . __('records');

            return $this->sendResponse(401, $message);
        }
    }

    // Delete record from database
    public function deleteRecord($request, $module, $email = null)
    {
        if ($module['link_field_value']) {
            $user_role = auth()->user()->role;

            $data = DB::table($module['table_name'])
                ->where($module['link_field'], $module['link_field_value'])
                ->first();

            if ($data) {
                $can_delete = true;

                if ($user_role != 'System Administrator') {
                    $role_permissions = $this->moduleWisePermissions($user_role, "Delete", $module['name']);

                    if ($role_permissions) {
                        foreach ($role_permissions as $column_name => $column_value) {
                            if (is_array($column_value)) {
                                if (!in_array($data->{$column_name}, $column_value)) {
                                    $can_delete = false;
                                    break;
                                }
                            } else {
                                if ($data->{$column_name} != $column_value) {
                                    $can_delete = false;
                                    break;
                                }
                            }
                        }
                    } else {
                        $can_delete = false;
                    }
                }

                if ($can_delete) {
                    $form_title = isset($module['form_title']) ? $data->{$module['form_title']} : $module['link_field_value'];

                    if ($form_title) {
                        // delete child tables if found
                        if (isset($module['child_tables']) && isset($module['child_foreign_key'])) {
                            foreach ($module['child_tables'] as $child_table) {
                                DB::table($child_table)
                                    ->where($module['child_foreign_key'], $module['link_field_value'])
                                    ->delete();
                            }
                        }

                        $result = DB::table($module['table_name'])
                            ->where($module['link_field'], $module['link_field_value'])
                            ->delete();

                        // update activity
                        $activity_data = [
                            'module' => $module['display_name'],
                            'icon' => $module['icon'],
                            'form_title' => $form_title,
                            'form_id' => $module['link_field_value']
                        ];

                        $this->saveActivity($activity_data, 'Delete');

                        // delete user if modules come under user_via_modules
                        if (in_array($module['name'], $this->user_via_modules)) {
                            if (isset($data->email_id) && $data->email_id) {
                                $email = $data->email_id;
                            } elseif (isset($data->email) && $data->email) {
                                $email = $data->email;
                            } else {
                                $email = null;
                            }

                            $this->userFormAction($request, $module['name'], "delete", $data->avatar, $email);
                        }

                        $delete_data = [
                            'form_data' => [$module['table_name'] => $data],
                            'link_field' => $module['link_field'],
                            'form_title' => isset($module['form_title']) ? $module['form_title'] : $module['link_field'],
                            'title' => $module['display_name'],
                            'icon' => $module['icon'],
                            'file' => $module['view'],
                            'module' => $module['name'],
                            'table_name' => $module['table_name']
                        ];

                        session()->flash('success', true);
                        $message = __($module['display_name']) . ': "' . __($form_title) . '" ' . __('deleted successfully');

                        return $this->sendResponse(200, $message, $delete_data);
                    } else {
                        session()->flash('success', false);
                        return $this->sendResponse(500, __('Some error occured. Please try again'));
                    }

                    // deletes the avatar file if any
                    if (isset($data->avatar) && $data->avatar) {
                        File::delete(public_path().$data->avatar);
                    }
                } else {
                    $form_title = isset($module['form_title']) ? $data->{$module['form_title']} : $data->{$module['link_field_value']};
                    $message = __('You are not authorized to delete') . ' "' . __($form_title) . '" ' . __('record');

                    return $this->sendResponse(401, $message);
                }
            } else {
                session()->flash('success', false);
                return $this->sendResponse(404, __('No such record found'));
            }
        } else {
            session()->flash('success', false);

            $message = __('Cannot delete the record') . '. "' . __($module['link_field']) . '" ' . __('is not set');
            return $this->sendResponse(400, $message);
        }
    }

    // Returns the array of data from request with some common data
    public function populateData($request, $module, $action = null)
    {
        $form_data = $request->all();
        $parent_foreign_keys = [];
        unset($form_data["_token"]);

        if (isset($form_data['password']) && $form_data['password']) {
            $form_data['password'] = bcrypt($form_data['password']);
        }

        if (isset($module['parent_foreign_map']) && count($module['parent_foreign_map'])) {
            foreach ($module['parent_foreign_map'] as $foreign_table => $details) {
                $parent_foreign_keys[$details['foreign_key']] = 1;
            }
        }

        foreach($request->files->all() as $field_name => $files) {
            if (is_array($files)) {
                foreach ($files as $idx => $child_details) {
                    foreach ($child_details as $child_field => $file) {
                        if ($file) {
                            $this->file_fields[$field_name][$idx] = $child_field;
                        }
                    }
                }
            } else {
                array_push($this->file_fields, $field_name);
            }
        }

        foreach ($this->file_fields as $index => $field) {
            if (isset($module['upload_folder']) && $module['upload_folder']) {
                if (is_string($field)) {
                    if (isset($form_data[$field]) && $form_data[$field]) {
                        $form_data[$field] = $this->createFilePath($request->file($field), $module['upload_folder']);
                    }
                } elseif (is_array($field)) {
                    foreach ($field as $idx => $child_field_name) {
                        $form_data[$index][$idx][$child_field_name] = $this->createFilePath($request->file($index)[$idx][$child_field_name], $module['upload_folder']);
                    }
                }
            }
        }

        // get the table schema
        $table_schema = $this->getTableSchema($module['table_name'], true);

        foreach ($form_data as $column => $value) {
            if (isset($table_schema[$column]) && $table_schema[$column]['datatype'] == "date" && $value) {
                $value = date('Y-m-d', strtotime($value));
            } elseif (isset($table_schema[$column]) && $table_schema[$column]['datatype'] == "datetime" && $value) {
                $value = date('Y-m-d H:i:s', strtotime($value));
            } elseif (isset($table_schema[$column]) && $table_schema[$column]['datatype'] == "time" && $value) {
                $value = date('H:i:s', strtotime($value));
            } elseif (!is_array($value) && $value && isset($table_schema[$column])) {
                // checking is array is important to eliminate convert type for child tables
                $this->convertDataType($value, $table_schema[$column]['datatype']);
            }

            if ($value) {
                if (isset($module['child_tables']) && in_array($column, $module['child_tables'])) {
                    $data[$column] = $value;
                } elseif (isset($table_schema[$column])) {
                    $data[$module['table_name']][$column] = $value;
                }
            } else {
                if ($parent_foreign_keys && count($parent_foreign_keys) && isset($parent_foreign_keys[$column])) {
                    if (isset($table_schema[$column])) {
                        if ($table_schema[$column]['nullable']) {
                            $data[$module['table_name']][$column] = null;
                        } else {
                            unset($data[$module['table_name']][$column]);
                        }
                    } else {
                        unset($data[$module['table_name']][$column]);
                    }
                } elseif (isset($table_schema[$column]) && in_array($table_schema[$column]['datatype'], ['integer', 'boolean'])) {
                    $data[$module['table_name']][$column] = 0;
                } else {
                    if ($module['link_field_value'] && isset($table_schema[$column])) {
                        $data[$module['table_name']][$column] = null;
                    }
                }
            }
        }

        $data = $this->mergeCommonData($data, $module, $action);
        // web_dump($data);
        return $data;
    }

    // converts the type of request value to the type to be inserted in db
    public function convertDataType($value, $type_name)
    {
        if (in_array($type_name, ['decimal', 'bigint'])) {
            $type_name = "float";
        } elseif ($type_name == "text") {
            $type_name = "string";
        }

        settype($value, $type_name);
    }

    // Returns the array of data from request with some common data and child data
    public function mergeCommonData($data, $module, $action = null)
    {
        $owner = $last_updated_by = auth()->user()->username;
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $parent_table = $module['table_name'];
        $child_foreign_keys = [];

        if (isset($module['child_foreign_map']) && count($module['child_foreign_map'])) {
            foreach ($module['child_foreign_map'] as $child_table => $foreign_details) {
                foreach ($foreign_details as $foreign_table => $details) {
                    $child_foreign_keys[$details['foreign_key']] = 1;
                }
            }
        }

        foreach ($data as $table => $table_data) {
            if ($table == $parent_table) {
                $data[$table]['last_updated_by'] = $last_updated_by;
                $data[$table]['updated_at'] = $updated_at;

                if ($action == "create") {
                    $data[$table]['owner'] = $owner;
                    $data[$table]['created_at'] = $created_at;
                }

                // check if module come under slug modules list
                if (in_array($module['name'], $this->slug_modules) && isset($module['slug_source']) && $module['slug_source']) {
                    $parent_field_name = 'slug';

                    // check if generated no is already present in record
                    $valid_slug = false;
                    do {
                        $generated_slug = Str::slug($data[$table][$module['slug_source']], "-");

                        $existing_slug = DB::table($table)
                            ->where($parent_field_name, $generated_slug)
                            ->pluck($parent_field_name);

                        if (!$existing_slug) {
                            $valid_slug = true;
                        }
                    } while ($valid_slug == false);

                    $data[$table][$parent_field_name] = $generated_slug;
                }
            } else {
                // get the table schema
                $table_schema = $this->getTableSchema($table, true);

                foreach (array_values($table_data) as $index => $child_record) {
                    if (isset($child_record['action']) && $child_record['action'] && isset($data[$table][$index])) {
                        if ($child_record['action'] == "none") {
                            unset($data[$table][$index]);
                        } else {
                            $child_data = $child_record;
                            unset($child_data['id'], $child_data['action']);

                            // check if child table has any parameter with value
                            if (count(array_filter($child_data)) === 0 && $child_record['action'] !== "delete") {
                                unset($data[$table][$index]);
                            } else {
                                if (isset($data[$table][$index]['id']) && $data[$table][$index]['id']) {
                                    $data[$table][$index]['id'] = (int) $data[$table][$index]['id'];
                                }
                                // insert foreign key of child table which connects to parent table link field
                                if (isset($data[$parent_table]) && isset($data[$parent_table][$module['link_field']])) {
                                    $data[$table][$index][$module['child_foreign_key']] = $data[$parent_table][$module['link_field']];
                                }
                                if (isset($module['copy_parent_fields']) && isset($data[$parent_table])) {
                                    foreach ($module['copy_parent_fields'] as $parent_field => $child_field) {
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
                                    } elseif (isset($table_schema[$column_name])) {
                                        if ($column_value && $table_schema[$column_name]['datatype'] == "date") {
                                            $data[$table][$index][$column_name] = date('Y-m-d', strtotime($column_value));
                                        } elseif ($column_value && $table_schema[$column_name]['datatype'] == "datetime") {
                                            $data[$table][$index][$column_name] = date('Y-m-d H:i:s', strtotime($column_value));
                                        } elseif ($column_value && $table_schema[$column_name]['datatype'] == "time") {
                                            $data[$table][$index][$column_name] = date('H:i:s', strtotime($column_value));
                                        } else {
                                            if ($column_value) {
                                                $this->convertDataType($column_value, $table_schema[$column_name]['datatype']);
                                            } else {
                                                if ($table_schema[$column_name]['datatype'] == "boolean") {
                                                    $data[$table][$index][$column_name] = 0;
                                                } else {
                                                    unset($data[$table][$index][$column_name]);
                                                }
                                            }
                                        }
                                    }

                                    if ($child_foreign_keys && count($child_foreign_keys) && isset($child_foreign_keys[$column_name]) && !$column_value) {
                                        unset($data[$table][$index][$column_name]);
                                    }
                                }

                                $data[$table][$index]['last_updated_by'] = $last_updated_by;
                                $data[$table][$index]['updated_at'] = $updated_at;

                                if ($child_record['action'] == "create") {
                                    $data[$table][$index]['owner'] = $owner;
                                    $data[$table][$index]['created_at'] = $created_at;
                                }
                            }
                        }
                    } else {
                        unset($data[$table][$index]);
                    }
                }
            }
        }

        return $data;
    }

    // performs form actions for user table
    public function userFormAction($request, $module, $action, $user_avatar = null, $email = null)
    {
        $user_table_name = cache('app_modules')['User']['table_name'];
        $user = DB::table($user_table_name);

        if ($request->filled('email_id')) {
            $email = $request->get('email_id');
        } elseif ($request->filled('email')) {
            $email = $request->get('email');
        }

        if ($request->filled('username')) {
            $username = $request->get('username');
        } else {
            $username = $email;
        }

        if ($action == "delete") {
            $result = $user->where('username', $usename)->delete();
        } else {
            $first_name = '';
            $last_name = null;

            if ($request->filled('first_name')) {
                $first_name = $request->get('first_name');
            }

            if ($first_name) {
                $full_name = $first_name;

                if ($request->filled('last_name')) {
                    $last_name = $request->get('last_name');
                    $full_name = $first_name . ' ' . $last_name;
                }
            } else {
                if ($request->filled('full_name')) {
                    $full_name = $request->get('full_name');
                } elseif ($request->filled('name')) {
                    $full_name = $request->get('name');
                }

                if ($full_name) {
                    $name = explode(" ", $full_name);
                    $first_name = head($name);
                    array_shift($name);

                    if ($name && count($name)) {
                        $last_name = implode(" ", $name);

                        if ($last_name) {
                            $last_name = trim($last_name);
                        }
                    }
                }
            }

            if ($first_name && $full_name) {
                $user_data = array(
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "full_name" => $full_name,
                    "username" => $username,
                    "email" => $email,
                    "active" => $request->filled('active') ? $request->get('active') : 1,
                    "last_updated_by" => auth()->user()->username,
                    "updated_at" => date('Y-m-d H:i:s')
                );

                if (isset($user_avatar) && $user_avatar) {
                    $user_data["avatar"] = $user_avatar;
                }

                if ($action == "create") {
                    if ($request->filled('password')) {
                        $password = $request->get('password');
                    } else {
                        $password = generate_password(10);
                    }

                    if ($request->filled('role')) {
                        $role = $request->get('role');
                    } else {
                        $role = $module;
                    }

                    $user_data["password"] = bcrypt($password);
                    $user_data["email_verification_code"] = Str::random(30);
                    $user_data["role"] = $role;
                    $user_data["owner"] = auth()->user()->username;
                    $user_data["created_at"] = date('Y-m-d H:i:s');
                    $result = $user->insert($user_data);

                    if ($result) {
                        // save user specific app settings
                        $this->createUserSettings($user_data["username"], $role);
                    }
                } elseif ($action == "update") {
                    $result = $user->where('username', $username)->update($user_data);
                }
            } else {
                return false;
            }
        }

        return $result;
    }

    // creates file name
    public function createFilePath($upload_file, $folder)
    {
        $file_name = $upload_file->hashName();
        $file_path = $folder . "/" . $file_name;

        return $file_path;
    }

    // checks for an existing record in the database
    public function checkIfExists($module)
    {
        $existing_record = false;

        if ($module['link_field_value']) {
            $existing_record = DB::table($module['table_name'])
                ->select($module['link_field'], $module['form_title'])
                ->where($module['link_field'], $module['link_field_value'])
                ->first();
        }

        return $existing_record ? true : false;
    }

    // send json response based on http status code
    public function sendResponse($status_code, $message, $data = null)
    {
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
            'data' => $data ? json_decode(json_encode($data), true) : []
        ];

        return $response_data;
    }
}
