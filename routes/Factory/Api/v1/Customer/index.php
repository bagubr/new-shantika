<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'    => 'customer',
], function() {
    Route::group(['namespace' => 'Customer'], function() {
        Route::post('login/email', 'AuthController@loginEmail');
        Route::post('login/phone', 'AuthController@loginPhone');
        Route::post('registration','AuthController@registerCustomer');
        Route::get('home', 'HomeController@home');
    });
    Route::get('fleet_lists','FleetController@index');
    Route::get('fleet_detail/{id}','FleetController@show');
    
    Route::group([
        'middleware'=>'api.auth.user'
    ], function() {
        
        
        Route::group(['namespace' => 'Customer'], function() {
            Route::get('routes/available', 'RouteController@getAvailableRoutes');
            Route::get('chats', 'ChatController@index');
            Route::get('profile', 'UserController@show');
            Route::post('update', 'UserController@update');
        });
    });
});