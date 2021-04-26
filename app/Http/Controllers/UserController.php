<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Exception;
use App\User;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Str;

class UserController extends Controller
{
    use CommonController;

    // define common variables
    public $module_config;

    public function __construct()
    {
        $this->module_config = [
            'parent_foreign_map' => [
                'oc_language' => [
                    'foreign_key' => 'locale',
                    'local_key' => 'locale',
                    'fetch_field' => 'oc_language.name as language'
                ]
            ],
        ];
    }

    // define what should process before save
    public function beforeSave($request)
    {
        // set full name
        if ($request->filled('first_name')) {
            $full_name = $request->get('first_name');

            if ($request->filled('title')) {
                $full_name = $request->get('title') . ' ' . $full_name;
            }

            if ($request->filled('last_name')) {
                $full_name = $full_name . ' ' . $request->get('last_name');
            }

            $request->offsetSet('full_name', $full_name);
        }

        return $this->validateUsername($request);
    }

    // put all functions to be performed after save
    public function afterSave($data)
    {
        $table_name = $data['table_name'];
        $form_data = $data['form_data'][$table_name];

        if (session()->has('newly_created')) {
            $this->createUserSettings($form_data['username']);
        }
    }

    // check if username is already registered
    public function validateUsername($request)
    {
        if ($request->get('username')) {
            $user_details = User::select('username', 'email');

            if ($request->id) {
                $user_details = $user_details->where('id', '!=', $request->get('id'));
            }

            $user_details = $user_details->where(function($query) use ($request) {
                    $query->where('username', $request->get('username'))
                        ->orWhere('email', $request->get('email'));
                })
                ->first();

            if ($user_details) {
                session()->flash('success', false);

                if ($user_details->username == $request->get('username')) {
                    $msg = __('Username') . ': "' . $user_details->username . '" ' . __('is already registered') . '.';
                } elseif ($user_details->email == $request->get('email')) {
                    $msg = __('Email') . ': "' . $user_details->email . '" ' . __('is already registered') . '.';
                }

                throw new Exception($msg);
            } else {
                session()->flash('success', true);
                return true;
            }
        } else {
            throw new Exception(__('Username is not provided'));
        }
    }

    // verify email address of user
    public function verifyUserEmail(Request $request, $token)
    {
        if ($token) {
            $user = User::where('email_verification_code', $token)->first();

            if ($user) {
                $update_details = [
                    'email_verification_code' => null,
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'active' => 1,
                    'first_login' => 1
                ];

                $result = User::where('id', $user->id)
                    ->update($update_details);

                if ($result) {
                    $msg = __('Email verified successfully. Please change password to continue');
                    return $this->processFirstLogin($user, $msg);
                } else {
                    $msg = __('Some error occured. Please try again');
                }
            } else {
                $msg = __('Invalid Token or Token Expired');
            }
        } else {
            $msg = __('Please provide token to verify email');
        }

        if ($request->ajax()) {
            return response()->json([
                'msg' => $msg
            ], 200);
        } else {
            return redirect()->route('show.app.login')->with(['msg' => $msg]);
        }
    }

    // ask user to reset password after first login
    public function processFirstLogin($user, $msg = null)
    {
        $email = $user->email ? $user->email : $user->username;

        if ($email) {
            Auth::logout();
            session()->flush();

            $token = strtolower(Str::random(64));
            $data = [
                'email' => $email,
                'token' => $token,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $result = DB::table('password_resets')
                ->insert($data);

            if ($result) {
                $msg = $msg ? $msg : __('This is your first login. Please change your password');
                return redirect()->route('password.reset', array('token' => $token))->with(['first_login_msg' => $msg]);
            }
        }
    }
}
