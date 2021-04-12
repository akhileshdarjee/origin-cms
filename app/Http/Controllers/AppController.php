<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;

class AppController extends Controller
{
    use CommonController;

    // show home page based on app settings
    public function showHome()
    {
        $app_page = $this->getAppSetting('home_page');
        $app_page = $app_page ? $app_page : 'modules';
        $app_page = 'show.app.' . $app_page;

        if (session()->has('msg')) {
            return redirect()->route($app_page)->with('msg', session('msg'));
        } else {
            return redirect()->route($app_page);
        }
    }

    public function editorUpload(Request $request)
    {
        if ($request->ajax()) {
            $messages = [
                'required' => __('Please provide image'),
                'image' => __('Please upload valid image file')
            ];

            $validator = Validator::make($request->all(), [
                'image' => 'required|image',
            ], $messages);

            if ($validator->fails()) {
                $data = [
                    'success' => false,
                    'message' => $validator->messages()->first()
                ];
            } else {
                $file_name = $request->file('image')->hashName();

                $upload_path = $request->file('image')->storeAs(
                    'uploads/editor_uploads', $file_name, 'public'
                );

                if ($upload_path) {
                    $data = [
                        'success' => true,
                        'file' => asset('storage/uploads/editor_uploads/' . $file_name)
                    ];
                } else {
                    $data = [
                        'success' => false,
                        'message' => __('Cannot save image. Please try again')
                    ];
                }
            }
        } else {
            $data = [
                'message' => __('Please upload the image from Ajax method'),
            ];
        }

        return response()->json($data, 200);
    }
}
