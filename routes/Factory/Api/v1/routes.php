<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
], function() {
    Route::post('checkUuid', 'AuthController@checkUuid');
    Route::get('privacy_policy', 'PrivacyPolicyController@index');
    Route::get('informations', 'InformationController@index');
    Route::get('social_media', 'SocialMediaController@index');
    Route::get('term_and_condition', 'TermAndConditionController@index');
    Route::get('faq', 'FaqController@index');
    Route::get('time', 'TimeClassificationController@index');
    
    Route::get('facility', 'FacilityController@index');
    Route::get('cities', 'CityController@index');
    Route::get('agencies', 'AgencyController@index');
    Route::get('agency_city', 'AgencyController@getWithCity');
    Route::get('layouts', 'LayoutController@index');
    Route::get('fleet_classes', 'FleetClassController@index');

    Route::get('slider_detail/{id}', 'SliderController@sliderDetail');
    Route::get('article_detail/{id}', 'ArticleController@articleDetail');
    Route::get('testimonial_detail/{id}', 'TestimonialController@testimonialDetail');

    Route::get('about_us', 'AboutController@index');

    Route::get('notification', 'NotificationController@index');
    Route::get('notification/unread', 'NotificationController@indexUnread');
    Route::post('notification/read', 'NotificationController@read');
    Route::post('notification/read/all', 'NotificationController@readAll');

    Route::get('payment_types', 'PaymentTypeController@index');

    Route::group([],base_path('routes/Factory/Api/v1/Agen/index.php'));
    Route::group([],base_path('routes/Factory/Api/v1/Customer/index.php'));
});