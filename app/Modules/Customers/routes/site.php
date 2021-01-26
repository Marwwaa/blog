<?php

/*
|--------------------------------------------------------------------------
| Customers Site Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your main "front office" application.
| Please note that this file is auto imported in the main routes file, so it will inherit the main "prefix"
| and "namespace", so don't edit it to add for example "api" as a prefix. 
*/
Route::group([
    'namespace' => 'Modules\Customers\Controllers\Site',
], function () {
    // list records
    Route::post('/login', 'Auth\LoginController@index');
    Route::post('/forget-password', 'Auth\ForgetPasswordController@index');
    Route::post('/reset-password', 'Auth\ResetPasswordController@index');
    Route::post('/create-account', 'Auth\RegisterController@index');

    Route::group([
        'middleware' => ['logged-in'],
    ], function () {
        Route::post('/me', 'UpdateAccountController@index');
        Route::post('/logout', 'Auth\LogoutController@index');
        Route::post('/add-device-token', 'UpdateAccountController@addDeviceToken');
        Route::post('/remove-device-token', 'UpdateAccountController@removeDeviceToken');
        Route::post('/cart', 'CustomersController@updateCart');
        // Route::get('/notifications', 'NotificationsController@index');
    });
    // Child routes
});
