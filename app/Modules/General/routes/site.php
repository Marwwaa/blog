<?php

/*
|--------------------------------------------------------------------------
| General Site Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your main "front office" application.
| Please note that this file is auto imported in the main routes file, so it will inherit the main "prefix"
| and "namespace", so don't edit it to add for example "api" as a prefix. 
*/
Route::group([
    'namespace' => 'Modules\General\Controllers\Site',
], function () {
    // list records
    Route::get('/about-us', 'AboutUsController@index');
    Route::get('/settings', 'SettingsController@index');
    Route::get('/terms-conditions', 'TermsAndConditions@index');
    Route::get('/privacy-policy', 'PrivacyPolicyController@index');
    // one record 
    Route::get('/home', 'HomeController@index');
    Route::get('/general/{id}', 'GeneralController@show');
    // Child routes
});