<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
], function() {
    Route::get('privacy_policy', 'PrivacyPolicyController@index');
    Route::get('informations', 'InformationController@index');
    Route::get('social_media', 'SocialMediaController@index');
    Route::get('customer_menu', 'CustomerMenuController@index');
    Route::get('term_and_condition', 'TermAndConditionController@index');
    Route::get('faq', 'FaqController@index');
    Route::get('chat', 'ChatController@index');
    Route::get('facility', 'FacilityController@index');

    Route::group([
        'prefix'    => 'agen'
    ],function() {
        Route::post('login', 'AuthController@loginEmail');
        Route::post('login/phone', 'AuthController@loginPhone');
        Route::post('login/uid', 'AuthController@loginUid');

        
        Route::group([
            'middleware'=> 'api.auth.agent'
        ], function() {
            Route::get('routes/available', 'RouteController@getAvailableRoutes');
            Route::get('fleet/layout', 'LayoutController@getFleetLayout');
            Route::get('fleet/{id}', 'FleetController@showFleet');
            Route::get('cities', 'CityController@index');
            Route::get('agencies', 'AgencyController@index');
        });
    });

    Route::group([
        'prefix'    => 'customer'
    ], function() {
        Route::post('login', 'AuthController@loginEmail');
        Route::post('login/phone', 'AuthController@loginPhone');
        Route::post('login/uid', 'AuthController@loginUid');

        Route::group([
            'middleware'=>'api.auth.user',
        ], function() {
            //
        });
    });
});