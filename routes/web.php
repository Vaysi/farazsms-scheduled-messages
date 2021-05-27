<?php

// Main Route
Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('/home', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Auth::routes();
// Override Logout Route
Route::get('logout','Auth\LoginController@logout')->name('logout');

// Dashboard Routes
Route::group(['namespace'=>'Dashboard','middleware'=>['auth','ShouldVerify']],function (){
    Route::get('/dashboard','DashboardController@index')->name('dashboard');
    // Webservice Settings
    Route::get('/webservice','DashboardController@webservice')->name('webservice');
    Route::patch('/webservice','DashboardController@webserviceUpdate')->name('webservice.update');
    // Contact Handler
    Route::get('/contacts','DashboardController@contacts')->name('contacts');
    Route::post('/contacts/store','DashboardController@contactsStore')->name('contacts.store');
    Route::patch('/contacts/update','DashboardController@contactsUpdate')->name('contacts.update');
    Route::delete('/contacts/delete','DashboardController@contactsDelete')->name('contacts.delete');
    // Event Handler
    Route::get('/events','DashboardController@events')->name('events');
    Route::post('/events/store','DashboardController@eventsStore')->name('events.store');
    Route::delete('/events/delete','DashboardController@eventsDelete')->name('events.delete');
    // Add Phone To Contact
    Route::post('/contacts/add','DashboardController@contactsAdd')->name('contacts.add');
    // Get Numbers From Contact
    Route::post('/contacts/numbers','DashboardController@contactsNumbers')->name('contacts.numbers');
    // Admin
    Route::group(['middleware'=>'admin'],function (){
        // Site Settings
        Route::get('/site','SettingsController@index')->name('site.index');
        Route::post('/site','SettingsController@update')->name('site.update');
        // Export
        Route::get('/export','DashboardController@export')->name('export');
        Route::post('/export','DashboardController@exportPost')->name('export.post');
        // Users
        Route::get('/users','DashboardController@users')->name('users.index');
    });
});

// Should Complete
Route::group(['namespace'=>'Dashboard','middleware'=>['auth']],function () {
    // Profile Settings
    Route::get('/settings','DashboardController@settings')->name('settings');
    Route::patch('/settings','DashboardController@settingsUpdate')->name('settings.update');
    // Pay
    Route::get('/pay', 'DashboardController@shouldPay')->name('shouldPay');
    Route::get('/pay/money', 'DashboardController@pay')->name('pay');
    Route::get('/pay/verify', 'DashboardController@verifyPayment')->name('pay.verify');
    // Ajax Calls
    Route::post('/ajax/phone','DashboardController@verifyPhone');
});

// Ajax Calls
Route::post('/ajax/reset','Dashboard\DashboardController@resetPass');

