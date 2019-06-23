<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Exception;
use App\User;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use CommonController;

    // define what should process before save
    public function beforeSave($request)
    {
        return $this->validateLoginId($request);
    }

    // put all functions to be performed after save
    public function afterSave($data)
    {
        $table_name = $data['table_name'];
        $form_data = $data['form_data'][$table_name];

        if (session()->has('newly_created')) {
            $this->createUserSettings($form_data['login_id']);
        }
    }

    // check if login id is already registered
    public function validateLoginId($request)
    {
        if ($request->get('login_id')) {
            $user_details = User::select('login_id', 'email');

            if ($request->id) {
                $user_details = $user_details->where('id', '!=', $request->get('id'));
            }

            $user_details = $user_details->where(function($query) use ($request) {
                    $query->where('login_id', $request->get('login_id'))
                        ->orWhere('email', $request->get('email'));
                })
                ->first();

            if ($user_details) {
                session()->flash('success', false);

                if ($user_details->login_id == $request->get('login_id')) {
                    $msg = 'Login ID: "' . $user_details->login_id . '" is already registered.';
                } elseif ($user_details->email == $request->get('email')) {
                    $msg = 'Email ID: "' . $user_details->email . '" is already registered.';
                }

                throw new Exception($msg);
            } else {
                session()->flash('success', true);
                return true;
            }
        } else {
            throw new Exception("Login ID is not provided");
        }
    }

    // verify email address of user
    public function verifyUserEmail(Request $request, $token)
    {
        if ($token) {
            $user = User::where('email_confirmation_code', $token)->first();

            if ($user) {
                $update_details = [
                    'email_confirmation_code' => null,
                    'email_confirmed' => 1,
                    'status' => 'Active',
                    'first_login' => 1
                ];

                $result = User::where('id', $user->id)
                    ->update($update_details);

                if ($result) {
                    $msg = "Email verified successfully. Please change password to continue";
                    return $this->processFirstLogin($user, $msg);
                } else {
                    $msg = "Some error occured while verifying email. Please try again.";
                }
            } else {
                $msg = "Invalid Token or Token Expired";
            }
        } else {
            $msg = "Please provide token to verify email address";
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
        $email = $user->email ? $user->email : $user->login_id;

        if ($email) {
            Auth::logout();
            session()->flush();

            $token = strtolower(str_random(64));
            $data = [
                'email' => $email,
                'token' => $token,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $result = DB::table('password_resets')
                ->insert($data);

            if ($result) {
                $msg = $msg ? $msg : "This is your first login. Please change your password";
                return redirect()->route('password.reset', array('token' => $token))->with(['first_login_msg' => $msg]);
            }
        }
    }
}
