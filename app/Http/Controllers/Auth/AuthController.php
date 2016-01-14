<?php

namespace App\Http\Controllers\Auth;

use DB;
use Auth;
use Session;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AppController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	/**
	 * Show the application login form.
	 *
	 * @return Response
	 */
	public function getLogin()
	{
		if (Auth::check()) {
			return redirect('/app/modules');
		}
		else {
			return view('login');
		}
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  LoginRequest  $request
	 * @return Response
	 */
	public function postLogin(Request $request) {
		$credentials = [
			'login_id' => $request->login_id,
			'password' => $request->password,
			'status' => 'Active'
		];

		if (Auth::attempt($credentials)) {
			$user = DB::table('tabUser')
				->select('full_name', 'avatar', 'role', 'login_id')
				->where('login_id', $credentials['login_id'])
				->first();

			$this->put_user_data_in_session($request, $user);
			return redirect('/app/modules');
		}

		return redirect('/login')->with([
			'msg' => 'Login ID or Password is incorrect',
		]);
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return Response
	 */
	public function getLogout(Request $request) {
		Auth::logout();
		Session::flush();
		return redirect('/login');
	}

	/**
	 * Puts the data to session.
	 *
	 * @return session
	 */
	public function put_user_data_in_session($request, $user) {
		// puts all user related data into session
		$user_data = [
			'user' => $user->full_name,
			'role' => $user->role,
			'login_id' => $user->login_id,
			'avatar' => $user->avatar
		];

		$request->session()->put($user_data);
		return true;
	}

	/**
	 * Shows app page to the application.
	 *
	 * @return Response
	 */
	public function showApp(Request $request, $action) {
		if (Auth::check()) {
			$user_role = $request->session()->get('role');
			return AppController::show_app_page($request, $action, $user_role);
		}
		else {
			return redirect('/login');
		}
	}
}