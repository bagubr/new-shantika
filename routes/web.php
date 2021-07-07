<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FleetClassController;
use App\Http\Controllers\FleetController;
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

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resources([
        'fleets' => FleetController::class,
        'fleetclass' => FleetClassController::class
    ]);
});
