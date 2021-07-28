<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'    => 'customer',
], function() {
    Route::group(['namespace' => 'Customer'], function() {
        Route::post('login/email', 'AuthController@loginEmail');
        Route::post('login/phone', 'AuthController@loginPhone');
        Route::post('registration','AuthController@registerCustomer');
        Route::post('home', 'HomeController@home');
        Route::get('payment_type', 'PaymentTypeController@getPaymentType');
        Route::get('routes/available', 'RouteController@getAvailableRoutes');
        Route::get('chats', 'ChatController@index');
        Route::post('order_tiket', 'OrderController@store');
        Route::post('create_payment', 'OrderController@createPayment');
        Route::post('riwayat', 'OrderController@index');
        Route::get('riwayat/{id}', 'OrderController@show');
        Route::get('tiket', 'OrderController@tiket');
    });
    Route::get('fleet_lists','FleetController@index');
    Route::get('fleet_detail/{id}','FleetController@show');
    
    Route::group([
        'middleware'=>'api.auth.user'
    ], function() {
        
        
        Route::group(['namespace' => 'Customer'], function() {
            Route::get('test','TestimonialController@index');
            Route::post('review','ReviewController@create');
            Route::get('profile', 'UserController@show');
            Route::post('update', 'UserController@update');
        });
    });
});