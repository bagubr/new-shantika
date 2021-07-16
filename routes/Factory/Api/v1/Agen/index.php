<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'    => 'agen',
],function() {
    Route::post('login', 'AuthController@loginEmail');
    Route::post('login/phone', 'AuthController@loginPhone');
    Route::post('login/uid', 'AuthController@loginUid');

    
    Route::group([
        'middleware'=> 'api.auth.agent',
    ], function() {
        Route::get('routes/available', 'RouteController@getAvailableRoutes');
        Route::get('fleet/layout', 'LayoutController@getFleetLayout');
        Route::get('fleet/{id}', 'FleetController@showFleet');

        Route::get('kelas_armada', 'FleetClassController@index');

        Route::group(['namespace'=>'Agent'], function() {

        });
    });
});