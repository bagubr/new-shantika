<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'    => 'agen',
],function() {
    Route::group(['namespace'=>'Agent'], function() {
        Route::post('login/email', 'AuthController@loginEmail');
        Route::post('login/phone', 'AuthController@loginPhone');
        Route::get('chats', 'ChatController@index');
    });
    
    Route::group([
        'middleware'=> 'api.auth.agent',
    ], function() {
        Route::get('fleet/layout', 'LayoutController@getFleetLayout');
        Route::get('fleet/{id}', 'FleetController@showFleet');

        Route::get('kelas_armada', 'FleetClassController@index');

        Route::group(['namespace'=>'Agent'], function() {
            Route::post('home', 'HomeController@home');
            Route::get('profile', 'UserController@show');
            Route::post('update', 'UserController@update');

            Route::get('routes/available', 'RouteController@getAvailableRoutes');

            Route::get('home', 'HomeController@home');
        
            Route::get('booking_expired_duration', 'BookingController@bookingExpiredDuration');

            Route::post('booking', 'BookingController@booking');
            Route::post('order_tiket', 'OrderController@order');
            
<<<<<<< HEAD
=======
            Route::post('riwayat/{order_detail}/update', 'OrderDetailController@editDataPenumpang');
>>>>>>> rilisv1
            Route::get('riwayat', 'OrderController@index');
            Route::get('riwayat/{id}', 'OrderController@show')->whereNumber('id');
            Route::get('riwayat/rating', 'RatingController@index');
            Route::get('riwayat/rating/{id}', 'RatingController@show');
            Route::get('riwayat/customer', 'OrderDetailController@possibleCustomer');
            Route::get('riwayat/customer/{id}', 'OrderDetailController@detailPossibleCustomer');

            Route::get('setoran', 'OrderController@setoran');
            Route::get('setoran/list', 'OrderController@showListSetoran');
            Route::get('setoran/list/detail', 'OrderController@showSetoran');

            Route::get('calculate_discount', 'OrderController@calculateDiscount');

            Route::post('exchange_ticket', 'OrderController@exchange');
            Route::post('exchange_ticket/confirm', 'OrderController@exchangeConfirm');
            Route::get('promo', 'PromoController@index');
<<<<<<< HEAD
=======
            Route::get('scan_qr_code', 'MembershipController@getData');
>>>>>>> rilisv1
        });
    });
});