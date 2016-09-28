<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Exception;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
	// define common variables
	public $form_config;

	public function __construct() {
		$this->form_config = [
			'module' => 'User',
			'module_label' => 'User',
			'module_icon' => 'fa fa-user',
			'table_name' => 'tabUser',
			'view' => 'layouts.user',
			'avatar_folder' => '/images/user',
			'link_field' => 'login_id',
			'link_field_label' => 'Login ID',
			'record_identifier' => 'login_id'
		];
	}


	// define what should process before save
	public function before_save($request) {
		return $this->check_login_id($request);
	}


	// check if login id is already registered
	public function check_login_id($request) {
		if ($request->login_id) {
			if ($request->id) {
				$user_details = DB::table('tabUser')
					->select('login_id', 'email')
					->where('id', '!=', $request->id);

				$user_details = $user_details->where(function($query) use ($request) {
					$query->where('login_id', $request->login_id)
						->orWhere('email', $request->email);
				});
			}
			else {
				$user_details = DB::table('tabUser')
					->select('login_id', 'email');

				$user_details = $user_details->where(function($query) use ($request) {
					$query->where('login_id', $request->login_id)
						->orWhere('email', $request->email);
				});
			}

			$user_details = $user_details->first();

			if ($user_details) {
				Session::put('success', 'false');
				if ($user_details->login_id == $request->login_id) {
					$msg = 'Login ID: "' . $user_details->login_id . '" is already registered.';
				}
				elseif ($user_details->email == $request->email) {
					$msg = 'Email ID: "' . $user_details->email . '" is already registered.';
				}

				throw new Exception($msg);
			}
			else {
				Session::put('success', 'true');
				return true;
			}
		}
		else {
			throw new Exception("Login ID is not provided");
		}
	}


	// verify email address of user
	public function verifyUserEmail(Request $request, $token) {
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
					return $this->process_first_login($user, $msg);
				}
				else {
					$msg = "Some error occured while verifying email. Please try again.";
				}
			}
			else {
				$msg = "Invalid Token or Token Expired";
			}
		}
		else {
			$msg = "Please provide token to verify email address";
		}

		if ($request->ajax()) {
			return response()->json([
				'msg' => $msg
			], 200);
		}
		else {
			return redirect()->route('show.login')->with(['msg' => $msg]);
		}
	}


	// ask user to reset password after first login
	public function process_first_login($user, $msg = null) {
		$email = $user->email ? $user->email : $user->login_id;

		if ($email) {
			Auth::logout();
			Session::flush();
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
				return redirect('password/reset/' . $token)->with(['first_login_msg' => $msg]);
			}
		}
	}
}