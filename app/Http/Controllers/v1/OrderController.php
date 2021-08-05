<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiCalculateDiscountRequest;
use App\Models\Route;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function calculateDiscount(ApiCalculateDiscountRequest $request) {
        $route = Route::find($request->route_id);
        $setting = Setting::first();
        $data = [
            'total_food'=>$request->is_food ? $route->fleet?->fleetclass?->price_food * $request->seat_count : 0,
            'total_travel'=>$request->is_travel ? $setting->travel * $request->seat_count : 0,
            'total_member'=>$request->is_member ? $setting->member * $request->seat_count : 0
        ];
        $data = array_merge($data, [
            'price_ticket'=>(int) $route->price,
            'total_price'=>($route->price * $request->seat_count + $data['total_food'] + $data['total_travel'] - $data['total_member'])
        ]);
        return $this->successResponse($data);
    }
}
