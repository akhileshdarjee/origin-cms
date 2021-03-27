<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\PermController;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    use CommonController;
    use PermController;

    public function getData(Request $request)
    {
        $status_modules = ['User'];

        $module = $request->get('module');
        $module_table = $this->getModuleTable($module);
        $field = $request->get('field');
        $query = $request->get('query');
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

        // permission fields from perm controller
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

        // only show active data for defined tables
        if (in_array($module, $status_modules) && !$list_view && !$report_view) {
            $data_query = $data_query->where('active', '1');
        }

        // show only unique rows for list view
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

        $data = array_values(json_decode(json_encode($data), true));
        return $data;
    }
}
