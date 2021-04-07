<?php

namespace App\Http\Controllers;

use DB;
use Str;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\PermController;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    use CommonController;
    use PermController;

    public function getData(Request $request)
    {
        $module = $request->get('module');
        $query = $request->get('query');

        if ($module == 'Universe') {
            $data = $this->getUniverseResults($query);
        } else {
            $module_table = $this->getModuleTable($module);
            $field = $request->get('field');
            $image_field = $request->get('image_field');

            $field = explode("+", $field);
            $fetch_fields = [];

            if ($request->filled('unique')) {
                $unique = json_decode($request->get('unique'));
            } else {
                $unique = false;
            }

            if ($request->filled('fetch_fields')) {
                $get_fields = $request->get('fetch_fields');

                foreach ($get_fields as $column) {
                    $column = explode("+", $column);
                    $fetch_fields = array_merge($fetch_fields, $column);
                }
            }

            $list_view = $this->checkListView($request);
            $report_view = $this->checkReportView($request);

            if ($unique || $list_view) {
                $fetch_fields = $field;
            }
            elseif ($report_view) {
                $fetch_fields = array_merge($field, ['id']);
            }
            else {
                $fetch_fields = count($fetch_fields) ? $fetch_fields : $field;
            }

            if ($request->has('image_field') && $request->get('image_field')) {
                $fetch_fields = array_merge($fetch_fields, [$request->get('image_field')]);
            }

            $data_query = DB::table($module_table)
                ->select($fetch_fields);

            if ($query) {
                $data_query = $data_query->where($field[0], 'like', '%' . $query . '%')
                    ->where($field[0], '<>', '')
                    ->whereNotNull($field[0]);
            }

            if (auth()->user()->role != 'System Administrator') {
                $perm_fields = $this->moduleWisePermissions(auth()->user()->role, 'Read', $module);

                if ($perm_fields) {
                    foreach ($perm_fields as $field_name => $field_value) {
                        if (is_array($field_value)) {
                            $data_query = $data_query->whereIn($field_name, $field_value);
                        } else {
                            $data_query = $data_query->where($field_name, $field_value);
                        }
                    }
                }
            }

            if ($unique) {
                $data_query = $data_query->distinct();
            }

            if ($query) {
                $data = $data_query->take(50);
            } else {
                $data = $data_query->take(20);
            }

            $data = $data_query->get();

            if ($data) {
                foreach ($data as $idx => $record) {
                    foreach ($record as $column => $value) {
                        $data[$idx]->{$column} = strval($value);

                        if ($column == $field[0] && !strval($value)) {
                            unset($data[$idx]);
                        }
                    }
                }
            }
        }

        $data = array_values(json_decode(json_encode($data), true));
        return $data;
    }

    public function getUniverseResults($query)
    {
        $pages = [
            ['label' => __('Modules'), 'value' => __('Modules'), 'redirect_to' => route('show.app.modules')], 
            ['label' => __('Activity'), 'value' => __('Activity'), 'redirect_to' => route('show.app.activity')], 
            ['label' => __('Settings'), 'value' => __('Settings'), 'redirect_to' => route('show.app.settings')], 
            ['label' => __('Profile'), 'value' => __('Profile'), 'redirect_to' => route('show.doc', ['slug' => 'user', 'id' => auth()->user()->id])],
        ];

        $allowed_modules = [];
        $reports = [];

        if (auth()->user()->role == "Administrator" && auth()->user()->username == "admin") {
            array_push($pages, ['label' => __('Backups'), 'value' => __('Backups'), 'redirect_to' => route('show.app.backups')]);
        }

        $modules = $this->getAppModules();

        if (auth()->user()->role == 'System Administrator') {
            foreach ($modules as $module_name => $config) {
                array_push($allowed_modules, [
                    'label' => '<b>' . __($config['display_name']) . '</b> ' . __('List'), 
                    'value' => __($config['display_name']) . ' ' . __('List'), 
                    'redirect_to' => route('show.list', $config['slug'])
                ]);
            }
        } else {
            $role_modules = $this->roleWiseModules(auth()->user()->role, "Read");

            if ($role_modules) {
                foreach ($modules as $module_name => $config) {
                    if (in_array($module_name, $role_modules)) {
                        array_push($allowed_modules, [
                            'label' => '<b>' . __($config['display_name']) . '</b> ' . __('List'), 
                            'value' => __($config['display_name']) . ' ' . __('List'), 
                            'redirect_to' => route('show.list', $config['slug'])
                        ]);
                    }
                }
            }
        }

        if (in_array(auth()->user()->role, ["System Administrator", "Administrator"])) {
            array_push($pages, ['label' => __('Reports'), 'value' => __('Reports'), 'redirect_to' => route('show.app.reports')]);
        }

        $app_reports = config('reports');

        foreach ($app_reports as $report_name => $report) {
            if (isset($report['allowed_roles']) && $report['allowed_roles'] && !in_array(auth()->user()->role, $report['allowed_roles'])) {
                continue;
            }

            array_push($reports, ['label' => __($report['label']), 'value' => __($report['label']), 'redirect_to' => route('show.report', Str::snake($report_name))]);
        }

        if ($reports && count($reports)) {
            $found = false;

            foreach ($pages as $idx => $page) {
                if ($page['label'] === __('Reports')) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                array_push($pages, ['label' => __('Reports'), 'value' => __('Reports'), 'redirect_to' => route('show.app.reports')]);
            }
        }

        $result = array_merge($pages, $allowed_modules, $reports);

        if ($query) {
            foreach ($result as $idx => $res) {
                if (!Str::contains(strip_tags(strtolower($res['value'])), strtolower($query))) {
                    unset($result[$idx]);
                }
            }

            if (count($result) < 50) {
                $tables = [];

                if (auth()->user()->role == 'System Administrator') {
                    foreach ($modules as $module_name => $config) {
                        array_push($tables, [
                            'name' => $config['table_name'], 
                            'fetch_fields' => ['id', $config['form_title']], 
                            'module' => $module_name, 
                            'module_slug' => $config['slug'], 
                            'module_label' => __($config['display_name'])
                        ]);
                    }
                } else {
                    $role_modules = $this->roleWiseModules(auth()->user()->role, "Read");

                    if ($role_modules) {
                        foreach ($modules as $module_name => $config) {
                            if (in_array($module_name, $role_modules)) {
                                array_push($tables, [
                                    'name' => $config['table_name'], 
                                    'fetch_fields' => ['id', $config['form_title']], 
                                    'module' => $module_name, 
                                    'module_slug' => $config['slug'], 
                                    'module_label' => __($config['display_name'])
                                ]);
                            }
                        }
                    }
                }

                foreach ($tables as $idx => $table) {
                    if (count($result) < 50) {
                        $more = 50 - count($result);

                        $data_query = DB::table($table['name'])
                            ->select($table['fetch_fields'])
                            ->where($table['fetch_fields'][1], 'like', '%' . $query . '%')
                            ->where($table['fetch_fields'][1], '<>', '')
                            ->whereNotNull($table['fetch_fields'][1]);

                        if (auth()->user()->role != 'System Administrator') {
                            $perm_fields = $this->moduleWisePermissions(auth()->user()->role, 'Read', $table['module']);

                            if ($perm_fields) {
                                foreach ($perm_fields as $field_name => $field_value) {
                                    if (is_array($field_value)) {
                                        $data_query = $data_query->whereIn($field_name, $field_value);
                                    } else {
                                        $data_query = $data_query->where($field_name, $field_value);
                                    }
                                }
                            }
                        }

                        $data = $data_query->distinct()
                            ->take($more)
                            ->get();

                        if ($data && count($data)) {
                            foreach ($data as $idx => $record) {
                                array_push($result, [
                                    'label' => $table['module_label'] . ' <b>' . __(strval($record->{$table['fetch_fields'][1]})) . '</b>', 
                                    'value' => $table['module_label'] . ' ' . __(strval($record->{$table['fetch_fields'][1]})), 
                                    'redirect_to' => route('show.doc', ['slug' => $table['module_slug'], 'id' => $record->id])
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }
}
