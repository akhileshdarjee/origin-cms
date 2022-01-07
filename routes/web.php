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
    Route::get('/', 'WebsiteController@showIndex')->name('show.website');

    // Authentication routes...
    Route::get('/admin', 'Auth\LoginController@getLogin')->name('show.app.login');
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login')->name('submit.login');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    // Password Reset routes...
    Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('/verify/email/{token}', 'UserController@verifyUserEmail')->name('verify.email');

    // Request that requires authorization...
    Route::group(['middleware' => 'auth'], function () {
        // App routes...
        Route::get('/home', 'AppController@showHome')->name('home');
        Route::get('/latest-notifications', 'ActivityController@getLatestActivities')->name('latest.notifications');
        Route::post('/editor-upload', 'AppController@editorUpload')->name('editor.upload');
        Route::post('/change-theme', 'SettingsController@changeTheme')->name('change.theme');

        // App Home page module routes...
        Route::get('/app/modules', 'ModuleController@show')->name('show.app.modules');
        Route::get('/app/reports', 'ReportController@show')->name('show.app.reports');
        Route::get('/app/activity', 'ActivityController@show')->name('show.app.activity');
        Route::get('/app/settings', 'SettingsController@show')->name('show.app.settings');
        Route::post('/app/settings', 'SettingsController@save')->name('save.app.settings');
        Route::post('/app/change-password', 'AppController@changePassword')->name('password.change');
        Route::post('/update-module-sequence', 'ModuleController@updateSequence')->name('update.module.sequence');
        Route::post('/import-from-csv', 'ImportController@import')->name('import.from.csv');

        // Backup routes...
        Route::get('/app/backups', 'BackupController@show')->name('show.app.backups');
        Route::get('/app/backups/download/{name}', 'BackupController@download')->name('download.app.backups');
        Route::post('/app/backups/delete/{name}', 'BackupController@delete')->name('delete.app.backups');
        Route::post('/app/backups/create', 'BackupController@create')->name('create.app.backups');

        // List View...
        Route::get('/list/{slug}', 'ListViewController@showList')->name('show.list');
        Route::post('/list/{slug}/update-sorting', 'ListViewController@updateSorting')->name('update.list.sorting');

        // Report View...
        Route::get('/app/report/{report_name}', 'ReportController@showReport')->name('show.report');

        // Autocomplete data...
        Route::get('/get-auto-complete', 'AutocompleteController@getData')->name('get.autocomplete');

        // App Form/Module routes...
        Route::get('/form/{slug}', 'OriginController@show')->name('new.doc');
        Route::post('/form/{slug}', 'OriginController@save')->name('create.doc');
        Route::get('/form/{slug}/{id}', 'OriginController@show')->name('show.doc');
        Route::get('/form/{slug}/draft/{id}', 'OriginController@copy')->name('copy.doc');
        Route::post('/form/{slug}/{id}', 'OriginController@save')->name('update.doc');
        Route::get('/form/{slug}/delete/{id}', 'OriginController@delete')->name('delete.doc');

        // App API routes...
        Route::group(['prefix' => 'api'], function () {
            Route::post('/doc/create/{slug}', 'OriginController@save')->name('api.create.doc');
            Route::get('/doc/list/{slug}', 'ListViewController@showList')->name('api.get.doclist');
            Route::get('/doc/{slug}/{id}', 'OriginController@show')->name('api.get.doc');
            Route::post('/doc/update/{slug}/{id}', 'OriginController@save')->name('api.update.doc');
            Route::get('/doc/delete/{slug}/{id}', 'OriginController@delete')->name('api.delete.doc');
        });
    });
});
