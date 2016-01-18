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

		// App routes...
		Route::get('/app', 'AppController@show_home');

		// App Home page module routes...
		Route::get('/app/modules', 'ModuleController@show');
		Route::get('/app/reports', 'ReportController@show');
		Route::get('/app/settings', 'SettingsController@show');
		Route::post('/app/settings', 'SettingsController@save');

		// List View...
		Route::get('/list/{module_name}', 'ListViewController@showList');

		// Report View...
		Route::get('/app/report/{report_name}', 'ReportController@showReport');

		// Autocomplete data...
		Route::get('/getAutocomplete', 'AutocompleteController@getAutocomplete');

		// App Form/Module routes...
		Route::get('/form/{module_name}', 'FormActions@show');
		Route::post('/form/{module_name}', 'FormActions@save');
		Route::get('/form/{module_name}/{id}', 'FormActions@show');
		Route::post('/form/{module_name}/{id}', 'FormActions@save');
		Route::get('/form/{module_name}/delete/{id}', 'FormActions@delete');

	});
});