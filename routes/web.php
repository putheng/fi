<?php

Route::get('/template', 'HomeController@template');

Route::model('users', 'App\Models\User');

Route::get('/', 'HomeController@index');

Route::group(array('prefix' => 'admin'), function () {

    # Error pages should be shown without requiring login
    Route::get('404', function () {
        return View('admin/404');
    });
    Route::get('500', function () {
        return View::make('admin/500');
    });

    # All basic routes defined here
    Route::get('signin', array('as' => 'signin', 'uses' => 'AuthController@getSignin'));
    Route::post('signin', 'AuthController@postSignin');
    Route::post('forgot-password', array('as' => 'forgot-password', 'uses' => 'AuthController@postForgotPassword'));

    Route::get('login', function () {
        return View::make('admin/login');
    });

    # Forgot Password Confirmation
    Route::get('forgot-password/{userId}/{passwordResetCode}', array('as' => 'forgot-password-confirm', 'uses' => 'AuthController@getForgotPasswordConfirm'));
    Route::post('forgot-password/{userId}/{passwordResetCode}', 'AuthController@postForgotPasswordConfirm');
});

Route::group(['prefix' => 'admin', 'middleware' => 'SentinelAdmin', 'as' => 'admin.'], function () {

    # Logout
    Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@getLogout'));

    # Reservations / Index
    Route::get('/', ['as' => 'reservations', 'uses' => 'ReservationsController@index']);
    Route::get('/reservations/{clinicId?}', ['uses' => 'ReservationsController@index']);
    Route::post('/reservations', ['uses' => 'ReservationsController@index']);
    Route::post('/reservations/update', ['as' => 'reservations.update', 'uses' => 'ReservationsController@update']);
    Route::post('/reservations/updateslot', ['as' => 'reservations.updateslot', 'uses' => 'ReservationsController@updateSlot']);
    Route::get('/reservations/delete/{id}', ['as' => 'reservations.delete', 'uses' => 'ReservationsController@delete']);
    Route::get('/reservations/slots/{clinicId}/{date}/{currentSlot}', ['as' => 'reservations.slots', 'uses' => 'ReservationsController@loadAvailableSlots']);
    Route::get('/reservations', ['as' => 'dashboard', 'uses' => 'ReservationsController@index']);
    Route::post('/reservations/sendlink', ['as' => 'reservations.sendlink', 'uses' => 'ReservationsController@sendLinkBySms']);

    # Reservations / QR Code
    Route::get('/qrcode/{code}', ['as' => 'qrcode', 'uses' => 'ReservationsController@qrCode']);

    # Reservations / Export
    Route::get('/export/index/{clinicId?}', ['as' => 'reservations.export.index', 'uses' => 'ExportDataController@index']);
    Route::post('/export/data', ['as' => 'reservations.export.data', 'uses' => 'ExportDataController@export']);

    # Reservations / Traffic
    Route::get('/trafficlogs/', ['as' => 'reservations.trafficlogs.index', 'uses' => 'TrafficLogsController@index']);
    Route::post('/trafficlogs/data', ['as' => 'reservations.trafficlogs.data', 'uses' => 'TrafficLogsController@export']);

    # User Management
    Route::group(array('prefix' => 'users', 'middleware' => 'permission_super_admin'), function () {
        Route::get('/', array('as' => 'users.index', 'uses' => 'UsersController@index'));
        Route::post('data', ['as' => 'users.data', 'uses' => 'UsersController@data']);
        Route::get('create', ['as' => 'users.create', 'uses' => 'UsersController@create']);
        Route::post('create', ['as' => 'users.store', 'uses' => 'UsersController@store']);
        Route::get('{user}/delete', array('as' => 'users.delete', 'uses' => 'UsersController@destroy'));
        Route::get('{user}/confirm-delete', array('as' => 'users.confirm-delete', 'uses' => 'UsersController@getModalDelete'));
        Route::get('{user}/restore', array('as' => 'restore/user', 'uses' => 'UsersController@getRestore'));
        Route::post('{user}/passwordreset', array('as' => 'passwordreset', 'uses' => 'UsersController@passwordreset'));
        Route::get('{user}/edit', ['as' => 'users.edit', 'uses' => 'UsersController@edit']);
    });
    //Route::resource('users', 'UsersController', ['middleware' => ['permission_super_admin', 'auth']]); //Include all controller's methods

    # Tokens
    Route::group(array('prefix' => 'tokens', 'middleware' => 'permission_admin'), function () {
        Route::get('/', ['as' => 'tokens.index', 'uses' => 'TokensController@index']);
        Route::post('data', ['as' => 'tokens.data', 'uses' => 'TokensController@data']);
        Route::get('{token}/delete', array('as' => 'tokens.delete', 'uses' => 'TokensController@delete'));
        Route::get('{token}/confirm-delete', array('as' => 'tokens.confirm-delete', 'uses' => 'TokensController@getModalDelete'));
        Route::get('create', ['as' => 'tokens.create', 'uses' => 'TokensController@create']);
        Route::post('create', ['as' => 'tokens.store', 'uses' => 'TokensController@store']);
        Route::get('{token}/edit', ['as' => 'tokens.edit', 'uses' => 'TokensController@edit']);
        Route::patch('{token}', ['as' => 'tokens.update', 'uses' => 'TokensController@update']);
    });

    # Geopoints
    Route::group(array('prefix' => 'geopoints', 'middleware' => 'permission_admin'), function () {
        Route::get('/', ['as' => 'geopoints.index', 'uses' => 'GeopointsController@index']);
        Route::post('data', ['as' => 'geopoints.data', 'uses' => 'GeopointsController@data']);
        Route::get('{geopoint}/delete', array('as' => 'geopoints.delete', 'uses' => 'GeopointsController@delete'));
        Route::get('{geopoint}/confirm-delete', array('as' => 'geopoints.confirm-delete', 'uses' => 'GeopointsController@getModalDelete'));
        Route::get('create', ['as' => 'geopoints.create', 'uses' => 'GeopointsController@create']);
        Route::post('create', ['as' => 'geopoints.store', 'uses' => 'GeopointsController@store']);
        Route::get('{geopoint}/edit', ['as' => 'geopoints.edit', 'uses' => 'GeopointsController@edit']);
        Route::patch('{geopoint}', ['as' => 'geopoints.update', 'uses' => 'GeopointsController@update']);
    });

    # Sites
    Route::group(array('prefix' => 'sites', 'middleware' => 'permission_admin'), function () {
        Route::get('/', ['as' => 'sites.index', 'uses' => 'SitesController@index']);
        Route::post('data', ['as' => 'sites.data', 'uses' => 'SitesController@data']);
        Route::get('{site}/delete', array('as' => 'sites.delete', 'uses' => 'SitesController@delete'));
        Route::get('{site}/confirm-delete', array('as' => 'sites.confirm-delete', 'uses' => 'SitesController@getModalDelete'));
        Route::get('create', ['as' => 'sites.create', 'uses' => 'SitesController@create']);
        Route::post('create', ['as' => 'sites.store', 'uses' => 'SitesController@store']);
        Route::get('{site}/edit', ['as' => 'sites.edit', 'uses' => 'SitesController@edit']);
        Route::patch('{site}', ['as' => 'sites.update', 'uses' => 'SitesController@update']);
    });

    # Chains
    Route::group(array('prefix' => 'chains', 'middleware' => 'permission_admin'), function () {
        Route::get('/', ['as' => 'chains.index', 'uses' => 'ChainsController@index']);
        Route::post('data', ['as' => 'chains.data', 'uses' => 'ChainsController@data']);
        Route::get('{chain}/delete', array('as' => 'chains.delete', 'uses' => 'ChainsController@delete'));
        Route::get('{chain}/confirm-delete', array('as' => 'chains.confirm-delete', 'uses' => 'ChainsController@getModalDelete'));
        Route::get('create', ['as' => 'chains.create', 'uses' => 'ChainsController@create']);
        Route::post('create', ['as' => 'chains.store', 'uses' => 'ChainsController@store']);
        Route::get('{chain}/edit', ['as' => 'chains.edit', 'uses' => 'ChainsController@edit']);
        Route::patch('{chain}', ['as' => 'chains.update', 'uses' => 'ChainsController@update']);
    });

    # Question
    Route::group(['prefix' => 'question', 'middleware' => 'permission_admin', 'as' => 'question.'], function(){
        Route::get('/', 'QuestionController@index')->name('index');

        Route::get('/results', 'ResultController@index')->name('result');
        Route::post('/results', 'ResultController@store');
        Route::get('/results/{result}/edit', 'ResultController@edit')->name('result.edit');
        Route::post('/results/{result}/edit', 'ResultController@update');

        Route::get('/results/{result}/delete', 'ResultController@destroy')->name('result.delete');

        Route::get('/create', 'QuestionController@create')->name('create');
        Route::post('/create', 'QuestionController@store');

        Route::post('/file/upload', 'UploadController@store')->name('upload');

        Route::get('/{question}/edit', 'QuestionController@edit')->name('edit');
        Route::post('/{question}/edit', 'QuestionController@storeUpdate');
        Route::get('/{question}/delete', 'QuestionController@deleteQ')->name('delete');

        Route::post('/{question}/file', 'QuestionController@storeFile')->name('file');

        Route::get('/{question}/answer', 'AnswerController@answerkh')->name('answer');
        Route::post('/{question}/answer', 'AnswerController@store');
        Route::get('/delete/answer/{answer}', 'AnswerController@destroy')->name('destroy');
    });

    # Clinics
    Route::group(array('prefix' => 'clinics', 'middleware' => 'permission_admin'), function () {
        Route::get('/', ['as' => 'clinics.index', 'uses' => 'ClinicsController@index']);

        Route::get('/export', ['as' => 'clinics.export', 'uses' => 'ClinicsExportController@export']);
        Route::get('/import', ['as' => 'clinics.import', 'uses' => 'ClinicsExportController@index']);
        Route::post('/import', ['uses' => 'ClinicsExportController@import']);

        Route::post('data', ['as' => 'clinics.data', 'uses' => 'ClinicsController@data']);
        Route::get('{clinic}/delete', array('as' => 'clinics.delete', 'uses' => 'ClinicsController@delete'));
        Route::get('{clinic}/confirm-delete', array('as' => 'clinics.confirm-delete', 'uses' => 'ClinicsController@getModalDelete'));
        Route::get('create', ['as' => 'clinics.create', 'uses' => 'ClinicsController@create']);
        Route::post('create', ['as' => 'clinics.store', 'uses' => 'ClinicsController@store']);
        Route::get('{clinic}/edit', ['as' => 'clinics.edit', 'uses' => 'ClinicsController@edit']);
    });

    # Edit my clinic
    Route::group(array('prefix' => 'clinics', 'middleware' => 'permission_user_single'), function () {
        Route::get('myclinic', ['as' => 'clinics.myclinic', 'uses' => 'ClinicsController@myClinic']);
        Route::patch('{clinic}', ['as' => 'clinics.update', 'uses' => 'ClinicsController@update']);
    });

    # Edit my user
    Route::group(array('prefix' => 'users'), function () {
        Route::get('myuser', ['as' => 'users.myuser', 'uses' => 'UsersController@myUser']);
        Route::patch('{user}', ['as' => 'users.update', 'uses' => 'UsersController@update']);
    });

    # SMS Job testing
    Route::group(array('prefix' => 'sms'), function () {
        Route::get('job', ['as' => 'sms.job', 'uses' => 'SMSJobController@runManual']);
    });

    # REMINDER Job testing
    Route::group(array('prefix' => 'reminder'), function () {
        Route::get('job', ['as' => 'reminder.job', 'uses' => 'ReminderJobController@runManual']);
    });
});
