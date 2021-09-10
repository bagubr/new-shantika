<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiCalculateDiscountRequest;
use App\Models\FleetRoute;
use App\Models\Route;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function calculateDiscount(ApiCalculateDiscountRequest $request) {
        $fleet_route = FleetRoute::find($request->fleet_route_id);
        $setting = Setting::first();

        $price_ticket = $fleet_route->price - $fleet_route->fleet_detail?->fleet?->fleetclass?->price_food;
        
        $data = [
            'total_food'=>$request->is_food 
                ? $fleet_route->fleet_detail?->fleet->fleetclass?->price_food * $request->seat_count 
                : 0,
            'total_travel'=>$request->is_travel ? $setting->travel * $request->seat_count : 0,
            'total_member'=>$request->is_member ? -($setting->member) * $request->seat_count : 0
        ];

        $price_with_food = $request->is_food 
            ? $fleet_route->price * $request->seat_count
            : $price_ticket * $request->seat_count; 
        
        $data = array_merge($data, [
            'price_ticket'=>$price_ticket,
            'total_price'=>$price_ticket + $data['total_travel'] + $data['total_member'] + ($request->is_food ? $data['for_food'] : $setting->default_food_price * $request->seat_count)
        ]);

        return $this->successResponse($data);
    }
}
