<?php

namespace App\Http\Controllers;

use Storage;
use App\Imports\ExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;

class ImportController extends Controller
{
    use CommonController;

    // import data from csv to database
    public function import(Request $request)
    {
        set_time_limit(0);

        if ($request->filled('module') && $request->hasFile('import_file')) {
            $excel_file = $request->file('import_file');

            if (in_array($excel_file->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                $module = $request->get('module');
                $file_name = "import_file." . $excel_file->getClientOriginalExtension();
                $excel_file->move(storage_path('app'), $file_name);
                $token = $request->header('X-CSRF-TOKEN');
                $module_slug = $this->getModuleSlug($module);

                if (Storage::exists($file_name)) {
                    $records = Excel::toArray(new ExcelImport([
                        'file' => $file_name
                    ]), storage_path('app/' . $file_name));

                    if ($records && count($records)) {
                        $records = $records[0];
                        $errors = [];
                        $saved = false;

                        foreach ($records as $record) {
                            $record['_token'] = $token;

                            foreach ($record as $key => $value) {
                                $value = trim($value);

                                if (in_array(strtolower($value), ['na', 'null']) || !$value && $value != '0') {
                                    $value = null;
                                }

                                if ($key == "password") {
                                    $value = bcrypt($value);
                                }

                                $record[$key] = $value;
                            }

                            if (isset($record['id']) && $record['id']) {
                                $module_request = Request::create(config('app.url') . '/api/doc/update/' . $module_slug . '/' . $record['id'], 'POST', $record);
                            } else {
                                $module_request = Request::create(config('app.url') . '/api/doc/create/' . $module_slug, 'POST', $record);
                            }

                            try {
                                $response_data = app()->handle($module_request);
                            }
                            catch(Exception $e) {
                                array_push($errors, str_replace("'", "", $e->getMessage()));
                                continue;
                            }

                            if (isset($response_data) && $response_data) {
                                $response = json_decode($response_data->getContent());

                                if (isset($response->status_code) && $response->status_code === 200) {
                                    $saved = true;
                                } elseif (isset($response->message)) {
                                    $saved = false;
                                    array_push($errors, $response->message);
                                }
                            } else {
                                array_push($errors, __('Data not saved. Please try again'));
                            }
                        }
                    }

                    if ($errors && count($errors)) {
                        return response()->json([
                            'success' => false,
                            'message' => $errors
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => true,
                            'message' => __('Import successful')
                        ], 200);
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('Only .csv, .xls or .xlsx files are allowed')
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => __('Please provide Module')
            ], 200);
        }
    }
}
