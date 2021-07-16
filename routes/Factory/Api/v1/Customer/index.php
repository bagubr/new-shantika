<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'    => 'customer',
], function() {
    Route::post('login', 'AuthController@loginEmail');
    Route::post('login/phone', 'AuthController@loginPhone');
    Route::post('login/uid', 'AuthController@loginUid');
    Route::post('registrasi','AuthController@registerCustomer');
    Route::get('customer_menu', 'CustomerMenuController@index');

    Route::group([
        'middleware'=>'api.auth.user'
    ], function() {
        
        Route::get('profile', 'UserController@show');
        Route::post('update', 'UserController@updage');

        Route::group(['namespace' => 'Customer'], function() {

        });
    });
});