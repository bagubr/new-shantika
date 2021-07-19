<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckpointController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FleetClassController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\TimeClassificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resources([
        'fleets' => FleetController::class,
        'fleetclass' => FleetClassController::class,
        'information' => InformationController::class,
        'faq' => FaqController::class,
        'privacy_policy' => PrivacyPolicyController::class,
        'layouts' => LayoutController::class,
        'routes' => RoutesController::class,
        'agency' => AgencyController::class,
        'chat' => ChatController::class,
        'checkpoint' => CheckpointController::class,
        'facility' => FacilityController::class,
        'customer_menu' => CustomerMenuController::class,
        'slider' => SliderController::class,
        'user' => UserController::class,
        'testimoni' => TestimoniController::class,
        'article' => ArticleController::class,
        'about' => AboutController::class,
        'time_classification' => TimeClassificationController::class,
        'social_media' => SocialMediaController::class
    ]);
});
