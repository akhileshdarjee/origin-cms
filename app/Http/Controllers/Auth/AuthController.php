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
			return redirect()->route('show.app');
		}
		else {
			$this->put_app_settings_in_session();
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
			$user = User::select('full_name', 'avatar', 'role', 'login_id')
				->where('login_id', $credentials['login_id'])
				->first();

			$this->put_user_data_in_session($user);

			if ($user->role == "Administrator") {
				$this->put_app_settings_in_session();
			}
			return redirect()->route('show.app');
		}

		return redirect()->route('show.login')->with([
			'msg' => 'Login ID or Password is incorrect',
			'success' => 'false'
		]);
	}


	/**
	 * Handle a login request to the application from website.
	 *
	 * @param  LoginRequest  $request
	 * @return Response
	 */
	public function postWebLogin(Request $request) {
		$credentials = [
			'login_id' => $request->get('login_id'),
			'password' => $request->get('password'),
			'role' => 'Customer',
			'status' => 'Active'
		];

		if (Auth::attempt($credentials)) {
			$user = User::select('full_name', 'avatar', 'role', 'login_id')
				->where('login_id', $credentials['login_id'])
				->first();

			$this->put_user_data_in_session($user);

			return "true";
		}
		else {
			return "false";
		}
	}


	/**
	 * Handle a register request to the application.
	 *
	 * @param  RegisterRequest  $request
	 * @return Response
	 */
	public function postRegister(Request $request) {
		Session::put('role', 'Website User');

		$cust = new CustomerController();
		$customer = $cust->saveForm($request);

		Session::forget('role');

		if ($customer && Session::get('success') == "true") {
			return redirect()->route('show.login')->with([
				'msg' => 'Your Account has been successfully created. Please Login.',
				'success' => 'true'
			]);
		}
		else {
			return back()->withInput()->with([
				'msg' => 'Some problem occurred, please try again...!!!',
				'success' => 'false'
			]);
		}
	}


	/**
	 * Log the user out of the application.
	 *
	 * @return Response
	 */
	public function getLogout(Request $request) {
		$user_role = Session::get('role');
		Auth::logout();
		Session::flush();

		// if customer was logged in then redirect to website
		if ($user_role == "Customer") {
			return redirect()->route('show.website');
		}
		else {
			return redirect()->route('show.login');
		}
	}

	/**
	 * Puts the data to session.
	 *
	 * @return session
	 */
	public function put_user_data_in_session($user) {
		// puts all user related data into session
		$user_data = [
			'user' => $user->full_name,
			'role' => $user->role,
			'login_id' => $user->login_id,
			'avatar' => $user->avatar
		];

		Session::put($user_data);
		return true;
	}


	/**
	 * Puts app settings data to session.
	 *
	 * @return session
	 */
	public function put_app_settings_in_session() {
		// puts app settings data into session
		$settings = DB::table('tabSettings')
			->select('field_name', 'field_value')
			->get();

		$app_settings = [];
		foreach ($settings as $setting) {
			$app_settings[$setting->field_name] = $setting->field_value;
		}

		Session::put('app_settings', $app_settings);
		return true;
	}


	/**
	 * Redirect the user to the Social authentication page.
	 *
	 * @return Response
	 */
	public function redirectToProvider($driver) {
		if (Session::has('app_settings')) {
			if (isset(Session::get('app_settings')['social_login']) && Session::get('app_settings')['social_login'] == "Active") {
				if ($this->driver_setting_value($driver) == "Active") {
					return Socialite::driver($driver)->redirect();
				}
				else {
					return redirect()->route('show.login')
						->with(['msg' => ucwords($driver) . ' Login is disabled']);
				}
			}
			else {
				return redirect()->route('show.login')
					->with(['msg' => 'Social Login is disabled']);
			}
		}
		else {
			return redirect()->route('show.login')
				->with(['msg' => 'Please set App Settings']);
		}
	}


	// handles social login
	public function handleProviderCallback($driver) {
		try {
			$user_profile = Socialite::driver($driver)->user();
		}
		catch (Exception $e) {
			return redirect()->route('show.login')
				->with(['msg' => 'Some problem occured. Please try again...!!!']);
		}

		$common_defaults = $this->getSocialUserData('default');
		$social_defaults = $this->getSocialUserData($driver);

		$user_details = $this->populate_user_data($common_defaults, $social_defaults, $user_profile, $driver);
		return $this->findOrCreateCustomer($user_details);
	}


	private function findOrCreateCustomer($user_details) {
		$user = User::where('login_id', $user_details['login_id'])
			->where('role', 'Customer')
			->first();

		if (!$user) {
			Session::put('role', 'Website User');
			$user_details['email_id'] = $user_details['email'];
			$request = Request::create('/form/customer', 'POST', $user_details);
			unset($user_details['email_id']);
			$cust = new CustomerController();
			$customer = $cust->saveForm($request);
			Session::forget('role');

			$user = User::where('login_id', $user_details['login_id'])
				->where('role', 'Customer')
				->first();
		}

		Auth::login($user, true);
		$this->put_user_data_in_session((object) $user_details);
		return redirect()->route('show.website');
	}


	// logins user if found or else creates an user
	private function findOrCreateUser($user_details) {
		$auth_user = User::where('login_id', $user_details['login_id'])->first();
		if (!$auth_user) {
			User::create($user_details);
			$auth_user = User::where('login_id', $user_details['login_id'])->first();
		}

		Auth::login($auth_user, true);
		$this->put_user_data_in_session((object) $user_details);
		return redirect()->route('show.website');
	}


	// create user data with defaults and social defaults
	private function populate_user_data($common_defaults, $social_defaults, $user_profile, $driver) {
		$user_details = [];

		foreach ($common_defaults as $column_name => $driver_column_name) {
			$user_details[$column_name] = $user_profile->$driver_column_name;
		}

		foreach ($social_defaults as $column_name => $driver_column_name) {
			if (is_array($driver_column_name)) {
				foreach ($driver_column_name as $column_key => $driver_column_key) {
					$user_details[$column_key] = $user_profile->user[$column_key][$driver_column_key];
				}
			}
			else {
				if ($column_name == "social_profile_url") {
					$user_details[$column_name] = $driver_column_name . $user_profile->id;
				}
				else {
					$user_details[$column_name] = $user_profile->user[$driver_column_name];
				}
			}
		}

		// Insert common user related fixed data
		$user_details['social_platform'] = ucwords($driver);
		$user_details['role'] = 'Customer';
		$user_details['status'] = 'Active';

		return $user_details;
	}


	// User data keys according to driver
	private function getSocialUserData($driver) {
		$user_data = [
			'default' => [
				'social_id' => 'id',
				'full_name' => 'name',
				'login_id' => 'email',
				'email' => 'email',
				'avatar' => 'avatar',
			],
			'facebook' => [
				'social_profile_url' => 'https://www.facebook.com/',
			],
			'google' => [
				'social_profile_url' => 'url',
			]
		];

		return $user_data[$driver];
	}


	// returns value of app setting according for driver
	public function driver_setting_value($driver) {
		$driver_setting_name = [
			'facebook' => 'facebook_login',
			'google' => 'google_login'
		];

		if (Session::has('app_settings') && isset(Session::get('app_settings')[$driver_setting_name[$driver]])) {
			return Session::get('app_settings')[$driver_setting_name[$driver]];
		}
		else {
			return false;
		}
	}
}