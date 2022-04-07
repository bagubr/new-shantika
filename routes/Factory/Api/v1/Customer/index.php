<?php

use App\Http\Controllers\v1\Customer\MembershipController;
use App\Http\Controllers\v1\Customer\PromoController;
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
        Route::post('order_tiket/upload', 'OrderController@upload');
        Route::post('create_payment', 'OrderController@createPayment');
        Route::post('riwayat', 'OrderController@index');
        Route::get('riwayat/{id}', 'OrderController@show');
        Route::get('tiket', 'OrderController@tiket');
        Route::post('review_list','ReviewController@index');
        Route::post('review','ReviewController@create');
        Route::get('membership', [MembershipController::class, 'index']);
        Route::get('membership/pointhistory', [MembershipController::class, 'pointHistory']);
        Route::get('souvenir', [MembershipController::class, 'listSouvenir']);
        Route::post('souvenir/redeem', [MembershipController::class, 'redeem']);
	 Route::get('souvenir/redeem/{id}/detail', [MembershipController::class, 'detailRedeem']);
        Route::get('souvenir/{id}', [MembershipController::class, 'showSouvenir']);
        Route::get('promo', [PromoController::class, 'index']);
    });
    Route::get('agencies','AgencyController@getAllAgen');
    Route::get('fleet_lists','FleetController@index');
    Route::get('fleet_class','FleetClassController@index');
    Route::get('fleet_detail/{id}','FleetController@show');

    Route::group([
        'middleware'=>'api.auth.user'
    ], function() {


        Route::group(['namespace' => 'Customer'], function() {
            Route::get('test','TestimonialController@index');
            Route::get('profile', 'UserController@show');
            Route::post('update', 'UserController@update');
        });
    });
});
