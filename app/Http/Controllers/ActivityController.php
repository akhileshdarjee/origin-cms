<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Carbon\Carbon;
use App\Activity;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\PermController;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    use CommonController;
    use PermController;

    public function show(Request $request)
    {
        $data = [
            'success' => true,
            'data' => [],
            'message' => __('Some error occured. Please try again')
        ];

        if ($request->ajax()) {
            $data['data']['activities'] = $this->getActivityRecords($request, true, 20);
            $data['data']['current_user_id'] = auth()->user()->id;

            return response()->json($data, 200);
        } else {
            $user_role = auth()->user()->role;
            $modules = $this->getAppModules();

            if ($user_role != 'System Administrator') {
                $role_modules = $this->roleWiseModules($user_role, "Read");

                if ($role_modules) {
                    foreach ($modules as $module_name => $config) {
                        if (!in_array($module_name, $role_modules)) {
                            unset($modules[$module_name]);
                        }
                    }
                }
            }

            $data['data']['modules'] = $modules;
            return view('admin.layouts.origin.activity')->with($data);
        }
    }

    // get module name and table of a role
    public function getRoleModules($role = null)
    {
        $modules = $this->getAppModules();
        $role = $role ? $role : auth()->user()->role;
        $role_modules = $this->roleWiseModules($role, "Read");
        $app_modules = [];

        if ($role_modules) {
            foreach ($modules as $module_name => $config) {
                if (in_array($module_name, $role_modules)) {
                    $app_modules[$module_name] = $config['table_name'];
                }
            }
        }

        return $app_modules;
    }

    // get all readable record ids of module
    public function getAllowedModuleRecordIds($modules)
    {
        $allowed_records = [];
        $error_msg = '';

        foreach ($modules as $module_name => $table_name) {
            try {
                $form_ids = DB::table($table_name);
                $perm_fields = $this->moduleWisePermissions(auth()->user()->role, 'Read', $module_name);

                if ($perm_fields) {
                    foreach ($perm_fields as $field_name => $field_value) {
                        if (is_array($field_value)) {
                            $form_ids = $form_ids->whereIn($field_name, $field_value);
                        } else {
                            $form_ids = $form_ids->where($field_name, $field_value);
                        }
                    }
                }

                $form_ids = $form_ids->pluck('id');

                if (is_array($form_ids) || is_object($form_ids)) {
                    $form_ids = json_decode(json_encode($form_ids), true);
                }

                $allowed_records[$module_name] = $form_ids;
            }
            catch (Exception $e) {
                $error_msg .= str_replace("'", "", $e->getMessage());
                continue;
            }
        }

        return $allowed_records;
    }

    public function getActivityRecords($request, $paginate = true, $size)
    {
        $activities = Activity::select(
            'id', 'user_id', 'user', 'module', 'icon', 'action', 
            'form_id', 'form_title', 'created_at', 'owner'
        );

        if (auth()->user()->role != 'System Administrator') {
            $modules = $this->getRoleModules();
            $module_ids_map = $this->getAllowedModuleRecordIds($modules);

            if ($module_ids_map && count($module_ids_map)) {
                $all_modules = $this->getAppModules();

                foreach ($module_ids_map as $module => $ids) {
                    if ($module != $all_modules[$module]['display_name']) {
                        $module_ids_map[$all_modules[$module]['display_name']] = $ids;
                        unset($module_ids_map[$module]);
                    }
                }

                $module_ids_map['Auth'] = $module_ids_map['User'];

                $activities = $activities->where(function($query) use($module_ids_map) {
                    foreach ($module_ids_map as $module => $ids) {
                        if ($module == "Auth") {
                            $id_column = 'user_id';
                        } else {
                            $id_column = 'form_id';
                        }

                        $query->orWhere(function($subquery) use($module, $ids, $id_column) {
                            $subquery->where('module', $module);
                            $subquery->whereIn($id_column, $ids);
                        });
                    }

                    $query->orWhere(function($subquery) use($module_ids_map) {
                        $subquery->where('action', 'Delete');
                        $subquery->whereIn('user_id', $module_ids_map['User']);
                    });

                    $query->orWhere(function($subquery) {
                        $subquery->where('owner', auth()->user()->username);
                    });
                });
            }
        }

        if ($request) {
            if ($request->filled('filters')) {
                $filters = $request->get('filters');

                foreach ($filters as $column => $value) {
                    $activities = $activities->where($column, $value);
                }
            }
        }

        $activities = $activities->orderBy('created_at', 'desc');

        if ($paginate) {
            $activities = $activities->paginate($size ? $size : 20);
        } elseif ($size) {
            $activities = $activities->take($size)
                ->get();
        } else {
            $activities = $activities->get();
        }

        return $activities;
    }

    public function getLatestActivities(Request $request)
    {
        $data = [
            'success' => true,
            'data' => [],
            'message' => __('Some error occured. Please try again')
        ];

        $size = $request->filled('size') ? intval(trim($request->get('size'))) : 5;
        $activities = $this->getActivityRecords(false, false, $size ? $size : 5);
        $activity_list = [];

        foreach ($activities as $act) {
            $icon = $act->icon;
            $action = $act->action;
            $module = $act->module;
            $description = '';
            $user = (auth()->user()->id == $act->user_id) ? __('You') : $act->user;
            $time_diff = Carbon::parse($act->created_at, 'UTC')->setTimezone(auth()->user()->time_zone)->diffForHumans();

            if ($act->module == "Auth") {
                if ($action == "Login") {
                    $description = $user . " " . __('logged in');
                } elseif ($action == "Logout") {
                    $description = $user . " " . __('logged out');
                }
            } else {
                $activity_link = __($act->module) . ': ' . $act->form_title;

                if ($action == "Create") {
                    $description = __('New') . " " . $activity_link . " " . __('created by') . " " . $user;
                } else if ($action == "Update") {
                    $description = $activity_link . " " . __('updated by') . " " . $user;
                } else if ($action == "Delete") {
                    $description = $activity_link . " " . __('deleted by') . " " . $user;
                } else if ($action == "Download") {
                    if ($act->module == "Report") {
                        $description = $act->form_title . " " . __('was downloaded by') . " " . $user;
                    } else {
                        $description = $activity_link . " " . __('was downloaded by') . " " . $user;
                    }
                }
            }

            array_push($activity_list, compact('icon', 'action', 'module', 'description', 'time_diff'));
        }

        $data['data']['notifications'] = $activity_list;
        return response()->json($data, 200);
    }
}
