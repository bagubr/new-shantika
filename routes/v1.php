<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post( 'login', 'AuthController@login');

Route::middleware(['api.auth'])->group(function() {
    Route::get('/', function() {
        return 'ay';
    });

    Route::get('privacy_policy', 'PrivacyPolicyController@index');
    Route::get('informations', 'InformationController@index');
    Route::get('social_media', 'SocialMediaController@index');
    Route::get('customer_menu', 'CustomerMenuController@index');
    Route::get('term_and_condition', 'TermAndConditionController@index');
    Route::get('faq', 'FaqController@index');
    Route::get('chat', 'ChatController@index');
    Route::get('facility', 'FacilityController@index');

    Route::get('routes/available', 'RouteController@getAvailableRoutes');
    Route::get('fleet/layout', 'LayoutController@getFleetLayout');
    Route::get('fleet/{id}', 'FleetController@showFleet');
});