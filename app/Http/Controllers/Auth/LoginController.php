<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    use CommonController;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the website login form.
     *
     * @return Response
     */
    public function showLoginForm()
    {
        return redirect()->route('show.app.login');
    }

    /**
     * Show the application login form.
     *
     * @return Response
     */
    public function getLogin()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        } else {
            return view('auth.login');
        }
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $user_credentials = $request->only($this->username(), 'password');
        $user_credentials['active'] = 1;

        return $user_credentials;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($request->filled('website')) {
            session()->put('website_login', true);
        }

        return $this->afterSuccessLogin($request);
    }

    // functions to be performed after successful login of user
    public function afterSuccessLogin($request)
    {
        $this->putAppSettingsInSession();
        $this->saveActivity(null, "Login");

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'msg' => 'User successfully logged in'
            ], 200);
        } else {
            if ($request->filled('redirect_to')) {
                return redirect($request->get('redirect_to'));
            } else {
                return redirect()->route('home');
            }
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if (auth()->user()) {
            $this->saveActivity(null, "Logout");
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('show.app.login');
    }
}
