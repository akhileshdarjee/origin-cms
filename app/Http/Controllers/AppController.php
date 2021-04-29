<?php

namespace App\Http\Controllers;

use Hash;
use App\User;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;

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

    // Change logged in user's password
    public function changePassword(Request $request)
    {
        $data = [
            'success' => false,
            'msg' => __('Some error occured. Please try again')
        ];

        if ($request->filled('current_password') && $request->filled('new_password') && $request->filled('new_password_confirmation')) {
            $current_password = trim($request->get('current_password'));
            $new_password = trim($request->get('new_password'));
            $new_password_confirmation = trim($request->get('new_password_confirmation'));

            if ($new_password == $current_password) {
                $data['msg'] = __('New Password & Current Password cannot be same');
            } else {
                if ($new_password == $new_password_confirmation) {
                    if (preg_match('~[A-Z]~', $new_password) && preg_match('~[a-z]~', $new_password) && preg_match('~\d~', $new_password) && !strrpos($new_password," ") && (strlen($new_password) > 6)) {
                        if (Hash::check(trim($request->get('current_password')), auth()->user()->password)) {
                            $password_updated = User::where('id', auth()->user()->id)
                                ->update(['password' => bcrypt($new_password), 'updated_at' => date('Y-m-d H:i:s')]);

                            if ($password_updated) {
                                $data = [
                                    'success' => true,
                                    'msg' => __('Password has been changed successfully')
                                ];
                            }
                        } else {
                            $data['msg'] = __('Current Password is invalid');
                        }
                    } else {
                        $data['msg'] = __('Password should be at least 8 characters including a number, an uppercase letter and a lowercase letter and should not contain any blank spaces');
                    }
                } else {
                    $data['msg'] = __('New Password and Confirm New Password does not match');
                }
            }
        } else {
            $data['msg'] = __('Please provide Current Password, New Password and Confirm New Password');
        }

        if ($request->ajax()) {
            return response()->json($data, 200);
        } else {
            return back()->with($data);
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
