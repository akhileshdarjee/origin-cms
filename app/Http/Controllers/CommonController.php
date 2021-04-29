<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use File;
use Carbon\Carbon;
use App\Module;
use App\Activity;
use App\User;

trait CommonController
{
    // save new activity data
    public function saveActivity($data, $action)
    {
        if (auth()->user()) {
            if ($action == "Login") {
                $data['module'] = 'Auth';
                $data['icon'] = 'fas fa-sign-in-alt';
            } elseif ($action == "Logout") {
                $data['module'] = 'Auth';
                $data['icon'] = 'fas fa-sign-out-alt';
            }

            $data['action'] = $action;
            $data['user'] = auth()->user()->full_name;
            $data['user_id'] = auth()->user()->id;
            $data['owner'] = $data['last_updated_by'] = auth()->user()->username;
            $data['created_at'] = $data['updated_at'] = Carbon::now('UTC')->format('Y-m-d H:i:s');

            Activity::insert($data);
        }
    }

    // get table slug from module
    public function getModuleSlug($module)
    {
        $app_modules = $this->getAppModules();

        if (isset($app_modules[$module]) && $app_modules[$module]) {
            if (isset($app_modules[$module]['slug']) && $app_modules[$module]['slug']) {
                return $app_modules[$module]['slug'];
            }
        }

        return false;
    }

    // get table name from module
    public function getModuleTable($module)
    {
        $app_modules = $this->getAppModules();

        if (isset($app_modules[$module]) && $app_modules[$module]) {
            if (isset($app_modules[$module]['table_name']) && $app_modules[$module]['table_name']) {
                return $app_modules[$module]['table_name'];
            }
        }

        return false;
    }

    // check is referer is list view
    public function checkListView($request)
    {
        $base_url = url('/') . "/";
        $referer = $request->server('HTTP_REFERER');
        $request_path = str_replace($base_url, "", $referer);
        $request_path = explode("/", $request_path);

        if (isset($request_path[0]) && $request_path[0] === "list") {
            return true;
        }

        return false;
    }

    // check is referer is report view
    public function checkReportView($request)
    {
        $base_url = url('/') . "/";
        $referer = $request->server('HTTP_REFERER');
        $request_path = str_replace($base_url, "", $referer);
        $request_path = explode("/", $request_path);

        if (isset($request_path[1]) && $request_path[1] === "report") {
            return true;
        }

        return false;
    }

    // create user specific app settings
    public function createUserSettings($username)
    {
        $settings = array(
            ['field_name' => 'home_page', 'field_value' => 'modules', 'module' => 'Other', 'owner' => $username, 'last_updated_by' => $username, 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), "updated_at" => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['field_name' => 'list_view_records', 'field_value' => '15', 'module' => 'Other', 'owner' => $username, 'last_updated_by' => $username, 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), "updated_at" => Carbon::now('UTC')->format('Y-m-d H:i:s')],
            ['field_name' => 'theme', 'field_value' => 'light', 'module' => 'Other', 'owner' => $username, 'last_updated_by' => $username, 'created_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), "updated_at" => Carbon::now('UTC')->format('Y-m-d H:i:s')]
        );

        DB::table('oc_settings')->insert($settings);
    }

    // Modules config such as icon, color, etc
    public function getAppModules()
    {
        if (cache()->has('app_modules')) {
            $app_modules = cache('app_modules');
        } else {
            $app_modules = cache()->rememberForever('app_modules', function() {
                $fields = [
                    'name', 'display_name', 'controller_name', 'slug', 'icon', 'icon_color', 
                    'bg_color', 'table_name', 'form_title', 'list_view_columns', 'image_field', 
                    'sort_field', 'sort_order'
                ];

                $module_defaults = Module::select($fields)
                    ->where('active', '1')
                    ->where('show', '1')
                    ->where('is_child_table', '0')
                    ->orderBy('sequence_no', 'asc')
                    ->get();

                foreach ($module_defaults as $idx => $module) {
                    $app_modules[$module->name] = [];

                    foreach ($fields as $idx => $field) {
                        $app_modules[$module->name][$field] = $module->$field;
                    }

                    $app_modules[$module->name]['link_field'] = 'id';
                    $app_modules[$module->name]['link_field_label'] = 'ID';
                    $app_modules[$module->name]['view'] = 'admin.layouts.modules.' . $module->slug;
                    $app_modules[$module->name]['upload_folder'] = '/uploads/' . $module->slug;
                }

                return $app_modules;
            });
        }

        return (array) $app_modules;
    }

    public function setModule($slug)
    {
        $module_data = '';
        $app_modules = $this->getAppModules();

        foreach ($app_modules as $module) {
            if ($module['slug'] == $slug) {
                $module_data = $module;
                break;
            }
        }

        if ($module_data) {
            if (File::exists(app_path('Http/Controllers/' . $module_data["controller_name"] . '.php'))) {
                $controller_file = app("App\\Http\\Controllers\\" . $module_data["controller_name"]);

                if ($controller_file) {
                    if (property_exists($controller_file, 'module_config')) {
                        $module_config = $controller_file->module_config;

                        if ($module_config && count($module_config)) {
                            foreach ($module_config as $key => $value) {
                                $module_data[$key] = $value;
                            }
                        }
                    }
                }
            }
        }
        else {
            throw new Exception(__('No Module found for slug') . ": " . $slug);
        }

        return $module_data;
    }

    // get app setting value
    public function getAppSetting($name = null)
    {
        if ($name) {
            return session('app_settings')[$name];
        } else {
            return session('app_settings');
        }
    }

    public function putAppSettingsInSession()
    {
        $settings = DB::table('oc_settings')
            ->select('field_name', 'field_value', 'owner')
            ->where('owner', auth()->user()->username)
            ->get();

        $app_settings = [];

        foreach ($settings as $setting) {
            $app_settings[$setting->field_name] = $setting->field_value;
        }

        session()->put('app_settings', $app_settings);
        return true;
    }

    // returns table column name and column type
    public function getTableSchema($table, $get_nullable = false)
    {
        $columns = DB::connection()
            ->getDoctrineSchemaManager()
            ->listTableColumns($table);

        $table_schema = [];

        foreach($columns as $column) {
            if ($get_nullable) {
                $table_schema[$column->getName()] = [
                    'datatype' => $column->getType()->getName(), 
                    'nullable' => !$column->getNotnull(),
                    // 'default' => $column->getDefault()
                ];
            } else {
                $table_schema[$column->getName()] = $column->getType()->getName();
            }
        }

        return $table_schema;
    }

    // update locale for user and update the session translations
    public function updateLocale($locale)
    {
        $user_updated = User::where('id', auth()->user()->id)
            ->update(['locale' => $locale, 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')]);

        if ($user_updated) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
            session()->forget('translations');

            $translations = [];

            if ($locale != 'en' && File::exists(resource_path('lang/' . $locale . '.json'))) {
                $translations = File::get(resource_path('lang/' . $locale . '.json'));
                $translations = json_decode($translations, false, 512, JSON_UNESCAPED_UNICODE);
            }

            session()->put('translations', $translations);
        }
    }

    // update user's timezone
    public function updateTimeZone($time_zone)
    {
        $user_updated = User::where('id', auth()->user()->id)
            ->update(['time_zone' => $time_zone, 'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')]);
    }
}
