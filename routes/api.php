<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('_callback')
    ->namespace('App\Http\Controllers\v1')
    ->group(function() {
        Route::post('xendit', 'PaymentController@callbackXendit');
        Route::get('change_order_status', 'PaymentController@ChangeOrderStatus');
    });


Route::prefix('v1')
    ->namespace('App\Http\Controllers\v1')
    ->group(base_path('routes/Factory/Api/v1/routes.php'));