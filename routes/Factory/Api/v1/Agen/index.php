<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'    => 'agen',
],function() {
    Route::group(['namespace'=>'Agent'], function() {
        Route::post('login/email', 'AuthController@loginEmail');
        Route::post('login/phone', 'AuthController@loginPhone');
    });
    
    Route::group([
        'middleware'=> 'api.auth.agent',
    ], function() {
        Route::get('fleet/layout', 'LayoutController@getFleetLayout');
        Route::get('fleet/{id}', 'FleetController@showFleet');

        Route::get('kelas_armada', 'FleetClassController@index');

        Route::get('home', 'HomeController@home');
        Route::group(['namespace'=>'Agent'], function() {
            Route::get('profile', 'UserController@show');
            Route::post('update', 'UserController@update');

            Route::get('routes/available', 'RouteController@getAvailableRoutes');

            Route::get('home', 'HomeController@home');
        
            Route::post('booking', 'BookingController@booking');
        });
    });
});