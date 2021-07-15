<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
], function() {
    Route::get('privacy_policy', 'PrivacyPolicyController@index');
    Route::get('informations', 'InformationController@index');
    Route::get('social_media', 'SocialMediaController@index');
    Route::get('term_and_condition', 'TermAndConditionController@index');
    Route::get('faq', 'FaqController@index');
    Route::get('chat', 'ChatController@index');
    Route::get('facility', 'FacilityController@index');
    Route::get('customer_menu', 'CustomerMenuController@index');

    Route::group([
        'prefix'    => 'agen'
    ],function() {
        Route::post('login', 'AuthController@loginEmail');
        Route::post('login/phone', 'AuthController@loginPhone');
        Route::post('login/uid', 'AuthController@loginUid');

        
        Route::group([
            'middleware'=> 'api.auth.agent',
            'namespace'=> 'Agent'
        ], function() {
            Route::get('routes/available', 'RouteController@getAvailableRoutes');
            Route::get('fleet/layout', 'LayoutController@getFleetLayout');
            Route::get('fleet/{id}', 'FleetController@showFleet');
            Route::get('cities', 'CityController@index');
            Route::get('agencies', 'AgencyController@index');

            Route::get('kelas_armada', 'FleetClassController@index');
        });
    });

    Route::group([
        'prefix'    => 'customer'
    ], function() {
        Route::post('login', 'AuthController@loginEmail');
        Route::post('login/phone', 'AuthController@loginPhone');
        Route::post('login/uid', 'AuthController@loginUid');
        Route::post('registrasi','AuthController@registerCustomer');

        Route::group([
            'middleware'=>'api.auth.user',
            'namespace'=>'Customer'
        ], function() {
            
            Route::get('profile', 'UserController@showCustomer');
            Route::post('update', 'UserController@updateProfileCustomer');

        });
    });
});