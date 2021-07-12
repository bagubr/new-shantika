<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FleetClassController;
use App\Http\Controllers\FleetController;
<<<<<<< HEAD
use App\Http\Controllers\InformationController;
use App\Http\Controllers\PrivacyPolicyController;
=======
use App\Http\Controllers\LayoutController;
>>>>>>> [add] a lot
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
<<<<<<< HEAD
        'information' => InformationController::class,
        'faq' => FaqController::class,
        'privacy_policy' => PrivacyPolicyController::class,
=======
        'layouts'=> LayoutController::class
>>>>>>> [add] a lot
    ]);
});
