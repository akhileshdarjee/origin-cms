<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\PermController;
use App\Http\Controllers\FormController;
use Illuminate\Http\Request;

class OriginController extends Controller
{
    use CommonController;
    use PermController;
    use FormController;

    public $module;

    /**
     * Returns parent and child table records
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug, $id = null)
    {
        try {
            $this->module = $this->setModule($slug);
        } catch(Exception $e) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status_code' => 500,
                    'status' => 'Internal Server Error',
                    'message' => $e->getMessage()
                ], 500);
            } else {
                return back()->withInput()->with(['msg' => $e->getMessage()]);
            }
        }

        $this->module['link_field_value'] = $id;
        $show_response = $this->showDoc($this->module);

        if ($request->is('api/*')) {
            // Send JSON response to API
            return response()->json($show_response, $show_response['status_code']);
        } else {
            // Returns response with view
            return $this->doAction($show_response);
        }
    }

    /**
     * Copy the parent and child table records to a new form
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request, $slug, $id = null)
    {
        try {
            $this->module = $this->setModule($slug);
        } catch(Exception $e) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status_code' => 500,
                    'status' => 'Internal Server Error',
                    'message' => $e->getMessage()
                ], 500);
            } else {
                return back()->withInput()->with(['msg' => $e->getMessage()]);
            }
        }

        $this->module['link_field_value'] = $id;
        $show_response = $this->showDoc($this->module);

        if (!$show_response['data']['permissions']['create']) {
            $show_response['status'] = "Unauthorized";
            $show_response['status_code'] = 401;
            $show_response['message'] = __('You are not authorized to create') . ' "'. __($this->module['display_name']) . '"';
            $show_response['data']['form_data'] = [];
        }

        if ($request->is('api/*')) {
            // Send JSON response to API
            return response()->json($show_response, $show_response['status_code']);
        } else {
            // Returns response with view
            return $this->doAction($show_response);
        }
    }

    /**
     * Stores/Saves the parent and child table records to the database
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, $slug, $id = null)
    {
        try {
            $this->module = $this->setModule($slug);
        } catch(Exception $e) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status_code' => 500,
                    'status' => 'Internal Server Error',
                    'message' => $e->getMessage()
                ], 500);
            } else {
                return back()->withInput()->with(['msg' => $e->getMessage()]);
            }
        }

        $this->module['link_field_value'] = $id;
        $controller_file = app("App\\Http\\Controllers\\" . $this->module["controller_name"]);

        if ($controller_file && method_exists($controller_file, 'beforeSave') && is_callable(array($controller_file, 'beforeSave'))) {
            try {
                call_user_func(array($controller_file, 'beforeSave'), $request);
            } catch(Exception $e) {
                if ($request->is('api/*')) {
                    return response()->json([
                        'status_code' => 500,
                        'status' => 'Internal Server Error',
                        'message' => $e->getMessage()
                    ], 500);
                } else {
                    return back()->withInput()->with(['msg' => $e->getMessage()]);
                }
            }
        }

        $save_response = $this->saveDoc($request, $this->module);

        if (isset($save_response['status_code']) && $save_response['status_code'] == 200) {
            if ($controller_file && method_exists($controller_file, 'afterSave') && is_callable(array($controller_file, 'afterSave'))) {
                call_user_func(array($controller_file, 'afterSave'), $save_response['data']);
            }
        }

        if ($request->is('api/*')) {
            // Send JSON response to API
            return response()->json($save_response, $save_response['status_code']);
        } else {
            // Returns response with view
            return $this->doAction($save_response, 'form_view');
        }
    }

    /**
     * Deletes the parent and child table records from the database
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $slug, $id = null)
    {
        try {
            $this->module = $this->setModule($slug);
        } catch(Exception $e) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status_code' => 500,
                    'status' => 'Internal Server Error',
                    'message' => $e->getMessage()
                ], 500);
            } else {
                return back()->withInput()->with(['msg' => $e->getMessage()]);
            }
        }

        $this->module['link_field_value'] = $id;
        $controller_file = app("App\\Http\\Controllers\\" . $this->module["controller_name"]);

        if ($controller_file && method_exists($controller_file, 'beforeDelete') && is_callable(array($controller_file, 'beforeDelete'))) {
            try {
                call_user_func(array($controller_file, 'beforeDelete'), $this->module['link_field_value']);
            } catch(Exception $e) {
                if ($request->is('api/*')) {
                    return response()->json([
                        'status_code' => 500,
                        'status' => 'Internal Server Error',
                        'message' => $e->getMessage()
                    ], 500);
                } else {
                    return back()->withInput()->with(['msg' => $e->getMessage()]);
                }
            }
        }

        $delete_response = $this->deleteDoc($request, $this->module);

        if (isset($delete_response['status_code']) && $delete_response['status_code'] == 200) {
            if ($controller_file && method_exists($controller_file, 'afterDelete') && is_callable(array($controller_file, 'afterDelete'))) {
                call_user_func(array($controller_file, 'afterDelete'), $delete_response['data']['form_data']);
            }
        }

        if ($request->is('api/*')) {
            // Send JSON response to API
            return response()->json($delete_response, $delete_response['status_code']);
        } else {
            // Returns response with view
            return $this->doAction($delete_response, 'list_view');
        }
    }

    // redirect to page based on api response
    public function doAction($response, $view_type = null)
    {
        $module_slug = $this->module['slug'];
        $form_data = isset($response['data']['form_data']) ? $response['data']['form_data'] : [];

        if (isset($response['status_code']) && $response['status_code'] == 200) {
            if ($view_type && $view_type == 'list_view') {
                return redirect()->route('show.list', array('slug' => $module_slug))
                    ->with(['msg' => $response['message']]);
            } elseif ($view_type && $view_type == 'form_view') {
                $form_link_field_value = $form_data[$this->module['table_name']][$this->module['link_field']];

                return redirect()->route('show.doc', array('slug' => $module_slug, 'id' => $form_link_field_value))
                    ->with(['msg' => $response['message']]);
            } else {
                if (debug_backtrace()[1]['function'] === "copy") {
                    // remove link field value from parent table
                    if ($this->module['link_field'] != "id") {
                        unset($response['data']['form_data'][$this->module['table_name']][$this->module['link_field']]);
                    }

                    unset($response['data']['form_data'][$this->module['table_name']]['id']);

                    // remove child foreign key from child tables
                    if (isset($this->module['child_tables']) && isset($this->module['child_foreign_key'])) {
                        foreach ($this->module['child_tables'] as $child_table) {
                            foreach ($response['data']['form_data'][$child_table] as $idx => $child_record) {
                                unset($response['data']['form_data'][$child_table][$idx]['id']);
                                unset($response['data']['form_data'][$child_table][$idx][$this->module['child_foreign_key']]);
                            }
                        }
                    }
                }

                return view('templates.form_view')->with($response['data']);
            }
        } elseif (isset($response['status_code']) && $response['status_code'] == 400) {
            session()->flash('success', false);
            return back()->withInput()->with(['msg' => $response['message']]);
        } elseif (isset($response['status_code']) && $response['status_code'] == 401) {
            session()->flash('success', false);

            if ($view_type && $view_type == 'list_view') {
                return redirect()->route('show.list', array('slug' => $module_slug))
                    ->with(['msg' => $response['message']]);
            } elseif ($view_type && $view_type == 'form_view') {
                return back()->withInput()->with(['msg' => $response['message']]);
            } else {
                return redirect()->route('home')->with('msg', $response['message']);
            }
        } elseif (isset($response['status_code']) && $response['status_code'] == 404) {
            session()->flash('success', false);

            if ($view_type && $view_type == 'list_view') {
                return redirect()->route('show.list', array('slug' => $module_slug))
                    ->with(['msg' => $response['message']]);
            } elseif ($view_type && $view_type == 'form_view') {
                $form_link_field_value = $form_data[$this->module['table_name']][$this->module['link_field']];

                return redirect()->route('show.doc', array('slug' => $module_slug, 'id' => $form_link_field_value))
                    ->with(['msg' => $response['message']]);
            } else {
                return redirect()->route('home')->with('msg', $response['message']);
            }
        } elseif (isset($response['status_code']) && $response['status_code'] == 500) {
            session()->flash('success', false);

            if ($view_type && $view_type == 'list_view') {
                return redirect()->route('show.list', array('slug' => $module_slug))
                    ->with(['msg' => $response['message']]);
            } elseif ($view_type && $view_type == 'form_view') {
                if (isset($form_data[$this->module['table_name']]) && 
                    isset($form_data[$this->module['table_name']][$this->module['link_field']])) {
                        $form_link_field_value = $form_data[$this->module['table_name']][$this->module['link_field']];

                        return redirect()->route('show.doc', array('slug' => $module_slug, 'id' => $form_link_field_value))
                            ->with(['msg' => $response['message']]);
                } else {
                    return back()->withInput()->with(['msg' => $response['message']]);
                }
            }
        }
    }
}
