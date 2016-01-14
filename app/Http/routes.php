<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
	// Website routes...
	Route::get('/', 'WebsiteController@showIndex');

	// API calls

	// Authentication routes...
	Route::get('/login', 'Auth\AuthController@getLogin');
	Route::post('/login', 'Auth\AuthController@postLogin');
	Route::get('/logout', 'Auth\AuthController@getLogout');

	// Password Reset routes...
	Route::get('password/email', 'Auth\PasswordController@getEmail');
	Route::post('password/email', 'Auth\PasswordController@postEmail');
	Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
	Route::post('password/reset', 'Auth\PasswordController@postReset');

	// Request that can be made without authorization


	Route::group(['middleware' => 'auth'], function () {

		// App routes
		Route::get('/app/{module}', 'Auth\AuthController@showApp');
		Route::get('/app', function () {
			return redirect('/app/modules');
		});

		// List View...
		Route::get('/list/{module_name}', 'ListViewController@showList');

		// Autocomplete data...
		Route::get('/getAutocomplete', 'AutocompleteController@getAutocomplete');

		// App routes...
		Route::get('/form/user', 'UserController@getForm');
		Route::post('/form/user', 'UserController@saveForm');
		Route::get('/form/user/{login_id}', 'UserController@getForm');
		Route::post('/form/user/{login_id}', 'UserController@saveForm');
		Route::get('/form/user/Delete/{login_id}', 'UserController@deleteForm');

	});
});