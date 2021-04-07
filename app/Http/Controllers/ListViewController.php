<?php

namespace App\Http\Controllers;

use DB;
use Exception;
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
            return back()->with(['msg' => $e->getMessage()]);
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
                        return redirect()->route('home')->with('msg', $msg);
                    } else {
                        return back()->with(['msg' => $msg]);
                    }
                }
            }

            return $this->showListView($request);
        }
    }

    public function showListView($request)
    {
        if ($request->ajax() || $request->is('api/*')) {
            $table_name = $this->module['table_name'];
            $columns = array_map('trim', explode(",", $this->module['list_view_columns']));
            $form_title = $this->module['form_title'];

            if ($request->filled('delete_list')) {
                $delete_data = $this->deleteSelectedRecords($request, $request->get('delete_list'));
                return response()->json(['data' => $delete_data], 200);
            }

            try {
                $list_view_data = $this->prepareListViewData($request);
                return $list_view_data;
            } catch(Exception $e) {
                return response()->json(['msg' => $e->getMessage()], 200);
            }
        }
        else {
            try {
                $list_view_data = $this->prepareListViewData($request);
                return view('templates.list_view', $list_view_data);
            } catch(Exception $e) {
                return back()->with(['msg' => $e->getMessage()]);
            }
        }
    }

    // prepare list view data
    public function prepareListViewData($request)
    {
        $user_role = auth()->user()->role;
        $table_schema = $this->getTableSchema($this->module['table_name']);

        if ($request->ajax() || $request->is('api/*')) {
            try {
                $rows = $this->getRecords($request, $table_schema);
            } catch(Exception $e) {
                $error = str_replace("'", "", $e->getMessage());
                throw new Exception($error);
            }
        } else {
            $rows = [];
        }

        if ($user_role == 'System Administrator') {
            $can_create = true;
            $can_delete = true;
        } else {
            $can_create = $this->roleWiseModules($user_role, "Create", $this->module['name']);
            $can_delete = $this->roleWiseModules($user_role, "Delete", $this->module['name']);
        }

        $list_view_data = [
            'module' => $this->module,
            'rows' => $rows,
            'columns' => array_map('trim', explode(",", $this->module['list_view_columns'])),
            'table_columns' => $table_schema,
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
                'msg' => session()->pull('msg'),
                'id' => $id
            ]);
        }

        return $result;
    }

    public function getRecords($request, $table_schema)
    {
        logger('getRecords');
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
                        $value = date('Y-m-d', strtotime($value));
                    } elseif (isset($table_schema[$column]) && $table_schema[$column] == "datetime" && $value) {
                        $value = date('Y-m-d H:i:s', strtotime($value));
                    } elseif (isset($table_schema[$column]) && $table_schema[$column] == "time" && $value) {
                        $value = date('H:i:s', strtotime($value));
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

    public function updateSorting(Request $request)
    {
        $data = [
            'success' => false,
            'msg' => __('Some error occured. Please try again')
        ];

        if ($request->filled('module') && $request->filled('sort_field') && $request->filled('sort_order')) {
            $module = trim($request->get('module'));
            $sort_field = trim($request->get('sort_field'));
            $sort_order = trim($request->get('sort_order'));

            $module_exists = Module::select('id', 'name')
                ->where('name', $module)
                ->first();

            if ($module_exists) {
                $updated = Module::where('name', $module)
                    ->update([
                        'sort_field' => $sort_field, 
                        'sort_order' => $sort_order, 
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                if ($updated) {
                    cache()->forget('app_modules');
                    $this->getAppModules();

                    $data = [
                        'success' => true,
                        'msg' => __('List View sorting fields updated for current module')
                    ];
                }
            } else {
                $data['msg'] = __('No such module found');
            }
        } else {
            $data['msg'] = __('Please provide module, sort field and sort order');
        }

        return response()->json($data, 200);
    }
}
