<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Carbon\Carbon;
use App\Module;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\PermController;
use Illuminate\Http\Request;

class ListViewController extends Controller
{
    use CommonController;
    use PermController;

    public $module;

    public function showList(Request $request, $slug)
    {
        try {
            $this->module = $this->setModule($slug);
        } catch(Exception $e) {
            return back()->with(['message' => str_replace("'", "", $e->getMessage())]);
        }

        if ($slug == "report") {
            return redirect()->route('show.app.reports');
        } else {
            $user_role = auth()->user()->role;

            if ($user_role != 'System Administrator') {
                $allowed = $this->roleWiseModules($user_role, "Read", $this->module["name"]);

                if (!$allowed) {
                    $msg = __('You are not authorized to view') . ' "'. __($this->module["display_name"]) . '" ' . __('records');

                    if (url()->current() === url()->previous()) {
                        return redirect()->route('home')->with(['message' => $msg]);
                    } else {
                        return back()->with(['message' => $msg]);
                    }
                }
            }

            return $this->showListView($request);
        }
    }

    public function showListView($request)
    {
        if ($request->ajax() || $request->is('api/*')) {
            if ($request->filled('delete_list')) {
                $delete_data = $this->deleteSelectedRecords($request, $request->get('delete_list'));
                return response()->json(['data' => $delete_data], 200);
            }

            try {
                $list_view_data = $this->prepareListViewData($request);
                return $list_view_data;
            } catch(Exception $e) {
                return response()->json(['message' => str_replace("'", "", $e->getMessage())], 200);
            }
        }
        else {
            try {
                $columns = array_map('trim', explode(",", $this->module['list_view_columns']));

                $records = DB::table($this->module['table_name'])
                    ->select($columns)
                    ->first();
            } catch(Exception $e) {
                return back()->with(['message' => str_replace("'", "", $e->getMessage())]);
            }

            try {
                $list_view_data = $this->prepareListViewData($request);
                return view('admin.templates.list_view', $list_view_data);
            } catch(Exception $e) {
                return back()->with(['message' => str_replace("'", "", $e->getMessage())]);
            }
        }
    }

    // prepare list view data
    public function prepareListViewData($request)
    {
        $user_role = auth()->user()->role;
        $table_schema = $this->getTableSchema($this->module['table_name']);
        $rows = [];
        $table_columns = [];

        if ($request->ajax() || $request->is('api/*')) {
            try {
                $rows = $this->getRecords($request, $table_schema);
            } catch(Exception $e) {
                throw new Exception(str_replace("'", "", $e->getMessage()));
            }
        }

        if ($user_role == 'System Administrator') {
            $can_create = true;
            $can_delete = true;
        } else {
            $can_create = $this->roleWiseModules($user_role, "Create", $this->module['name']);
            $can_delete = $this->roleWiseModules($user_role, "Delete", $this->module['name']);
        }

        foreach ($table_schema as $column_name => $column_type) {
            if (!in_array($column_name, ['avatar', 'password', 'remember_token'])) {
                $column_label = str_replace("Id", "ID", awesome_case($column_name));
                $column_label = explode(" ", $column_label);

                foreach ($column_label as $c_idx => $col_part) {
                    if ($col_part == 'Bg') {
                        $column_label[$c_idx] = 'Background';
                    }
                }

                $column_label = implode(" ", $column_label);

                $table_columns[$column_name] = [
                    'label' => __($column_label),
                    'type' => $column_type
                ];
            }
        }

        $list_view_data = [
            'module' => $this->module,
            'rows' => $rows,
            'columns' => array_map('trim', explode(",", $this->module['list_view_columns'])),
            'table_columns' => $table_columns,
            'can_create' => $can_create,
            'can_delete' => $can_delete
        ];

        return $list_view_data;
    }

    // filter list view data based on filter value
    public function deleteSelectedRecords($request, $delete_records)
    {
        $result = [];
        $origin_controller = app("App\\Http\\Controllers\\OriginController");

        foreach ($delete_records as $id) {
            $origin_controller->delete($request, $this->module['slug'], $id);

            array_push($result, [
                'success' => session()->pull('success'),
                'message' => session()->pull('message'),
                'id' => $id
            ]);
        }

        return $result;
    }

    public function getRecords($request, $table_schema)
    {
        $table = $this->module['table_name'];
        $table_columns = array_map('trim', explode(",", $this->module['list_view_columns']));
        $sort_field = $this->module['sort_field'];
        $sort_order = $this->module['sort_order'];

        if ($request->filled('sorting')) {
            $sort_filter = $request->get('sorting');

            if (isset($sort_filter['field']) && $sort_filter['field'] && 
                isset($sort_filter['order']) && $sort_filter['order']) {

                $sort_field = $sort_filter['field'];
                $sort_order = $sort_filter['order'];
            }
        }

        $perm_fields = [];
        $records_per_page = $this->getAppSetting('list_view_records');

        if (!in_array("id", $table_columns)) {
            array_push($table_columns, "id");
        }

        $rows = DB::table($table)->select($table_columns);

        if (auth()->user()->role != 'System Administrator') {
            $perm_fields = $this->moduleWisePermissions(auth()->user()->role, 'Read', $this->module['name']);

            if ($perm_fields) {
                foreach ($perm_fields as $field_name => $field_value) {
                    if (is_array($field_value)) {
                        $rows = $rows->whereIn($field_name, $field_value);
                    } else {
                        $rows = $rows->where($field_name, $field_value);
                    }
                }
            }
        }

        if ($request->filled('filters')) {
            $filters = $request->get('filters');

            foreach ($filters as $filter) {
                if (isset($filter['column_name']) && $filter['column_name'] && 
                    isset($filter['column_operator']) && $filter['column_operator'] &&
                    array_key_exists('column_value', $filter)) {

                    $column = $filter['column_name'];
                    $operator = $filter['column_operator'];
                    $value = $filter['column_value'];

                    if (isset($table_schema[$column]) && $table_schema[$column] == "date" && $value) {
                        $value = Carbon::parse($value, auth()->user()->time_zone)->setTimezone('UTC')->format('Y-m-d');
                    } elseif (isset($table_schema[$column]) && $table_schema[$column] == "datetime" && $value) {
                        $value = Carbon::parse($value, auth()->user()->time_zone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                    } elseif (isset($table_schema[$column]) && $table_schema[$column] == "time" && $value) {
                        $value = Carbon::parse($value, auth()->user()->time_zone)->setTimezone('UTC')->format('H:i:s');
                    }

                    if ($operator == "like") {
                        $rows = $rows->where($column, $operator, "%" . $value . "%");
                    } elseif ($operator == "in") {
                        if (!is_array($value)) {
                            $value = explode(",", $value);
                            $value = array_map('trim', $value);
                        }

                        $rows = $rows->whereIn($column, $value);
                    } elseif ($operator == "notin") {
                        if (!is_array($value)) {
                            $value = explode(",", $value);
                            $value = array_map('trim', $value);
                        }

                        $rows = $rows->whereNotIn($column, $value);
                    } elseif ($operator == "between") {
                        if (!is_array($value)) {
                            $value = explode(",", $value);
                            $value = array_map('trim', $value);
                        }

                        $rows = $rows->whereBetween($column, $value);
                    } elseif ($operator == "notbetween") {
                        if (!is_array($value)) {
                            $value = explode(",", $value);
                            $value = array_map('trim', $value);
                        }

                        $rows = $rows->whereNotBetween($column, $value);
                    } else {
                        if ($value) {
                            $rows = $rows->where($column, $operator, $value);
                        } else {
                            $rows = $rows->where(function($query) use ($column) {
                                $query->orWhere($column, '')
                                    ->orWhere($column, '0')
                                    ->orWhereNull($column);
                            });
                        }
                    }
                }
            }
        }

        $rows = $rows->orderBy($sort_field, $sort_order)
            ->paginate((int) $records_per_page);

        return $rows;
    }

    public function updateSorting(Request $request, $slug)
    {
        $data = [
            'success' => false,
            'message' => __('Some error occured. Please try again')
        ];

        try {
            $this->module = $this->setModule($slug);
        } catch(Exception $e) {
            $data['message'] = str_replace("'", "", $e->getMessage());
            return back()->with($data);
        }

        if ($request->filled('sort_field') && $request->filled('sort_order')) {
            $sort_field = trim($request->get('sort_field'));
            $sort_order = trim($request->get('sort_order'));

            $updated = Module::where('name', $this->module['name'])
                ->update([
                    'sort_field' => $sort_field, 
                    'sort_order' => $sort_order, 
                    'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s')
                ]);

            if ($updated) {
                cache()->forget('app_modules');
                $this->getAppModules();

                $data = [
                    'success' => true,
                    'message' => __('Sorting fields has been updated')
                ];
            }
        } else {
            $data['message'] = __('Please provide sort field and sort order');
        }

        return response()->json($data, 200);
    }
}
