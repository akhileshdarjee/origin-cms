<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web']], function () {
    // Website routes...
    Route::get('/', ['as' => 'show.website', 'uses' => 'WebsiteController@showIndex']);

    // Authentication routes...
    Route::get('/admin', ['as' => 'show.app.login', 'uses' => 'Auth\LoginController@getLogin']);
    Route::get('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::post('/login', ['as' => 'submit.login', 'uses' => 'Auth\LoginController@login']);
    Route::get('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

    // Password Reset routes...
    Route::get('/password/reset', ['as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
    Route::post('/password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
    Route::get('/password/reset/{token}', ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
    Route::post('/password/reset', ['as' => 'password.update', 'uses' => 'Auth\ResetPasswordController@reset']);
    Route::get('/verify/email/{token}', ['as' => 'verify.email', 'uses' => 'UserController@verifyUserEmail']);

    // Request that requires authorization...
    Route::group(['middleware' => 'auth'], function () {

        // App routes...
        Route::get('/home', ['as' => 'home', 'uses' => 'AppController@showHome']);

        // App Home page module routes...
        Route::get('/app/modules', ['as' => 'show.app.modules', 'uses' => 'ModuleController@show']);
        Route::get('/app/reports', ['as' => 'show.app.reports', 'uses' => 'ReportController@show']);
        Route::get('/app/activities', ['as' => 'show.app.activities', 'uses' => 'ActivityController@show']);
        Route::get('/app/settings', ['as' => 'show.app.settings', 'uses' => 'SettingsController@show']);
        Route::post('/app/settings', ['as' => 'save.app.settings', 'uses' => 'SettingsController@save']);
        Route::post('/update_module_sequence', ['as' => 'update.module.sequence', 'uses' => 'ModuleController@updateSequence']);
        Route::post('/import_from_csv', ['as' => 'import.from.csv', 'uses' => 'ImportController@import']);
        Route::post('/update_list_sorting', ['as' => 'update.list.sorting', 'uses' => 'ListViewController@updateSorting']);

        // Backup routes...
        Route::get('/app/backups', ['as' => 'show.app.backups', 'uses' => 'BackupController@show']);
        Route::get('/app/backups/download/{name}', ['as' => 'download.app.backups', 'uses' => 'BackupController@download']);
        Route::post('/app/backups/delete/{name}', ['as' => 'delete.app.backups', 'uses' => 'BackupController@delete']);
        Route::post('/app/backups/create', ['as' => 'create.app.backups', 'uses' => 'BackupController@create']);

        // List View...
        Route::get('/list/{slug}', ['as' => 'show.list', 'uses' => 'ListViewController@showList']);

        // Report View...
        Route::get('/app/report/{report_name}', ['as' => 'show.report', 'uses' => 'ReportController@showReport']);

        // Autocomplete data...
        Route::get('/get_auto_complete', ['as' => 'get.autocomplete', 'uses' => 'AutocompleteController@getData']);

        // App Form/Module routes...
        Route::get('/form/{slug}', ['as' => 'new.doc', 'uses' => 'OriginController@show']);
        Route::post('/form/{slug}', ['as' => 'create.doc', 'uses' => 'OriginController@save']);
        Route::get('/form/{slug}/{id}', ['as' => 'show.doc', 'uses' => 'OriginController@show']);
        Route::get('/form/{slug}/draft/{id}', ['as' => 'copy.doc', 'uses' => 'OriginController@copy']);
        Route::post('/form/{slug}/{id}', ['as' => 'update.doc', 'uses' => 'OriginController@save']);
        Route::get('/form/{slug}/delete/{id}', ['as' => 'delete.doc', 'uses' => 'OriginController@delete']);

        // App API routes...
        Route::group(['prefix' => 'api'], function () {
            Route::post('/doc/create/{slug}', ['as' => 'api.create.doc', 'uses' => 'OriginController@save']);
            Route::get('/doc/list/{slug}', ['as' => 'api.get.doclist', 'uses' => 'ListViewController@showList']);
            Route::get('/doc/{slug}/{id}', ['as' => 'api.get.doc', 'uses' => 'OriginController@show']);
            Route::post('/doc/update/{slug}/{id}', ['as' => 'api.update.doc', 'uses' => 'OriginController@save']);
            Route::get('/doc/delete/{slug}/{id}', ['as' => 'api.delete.doc', 'uses' => 'OriginController@delete']);
        });
    });
});
