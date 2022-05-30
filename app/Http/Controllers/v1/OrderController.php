<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiCalculateDiscountRequest;
use App\Models\FleetRoute;
use App\Models\Route;
use App\Models\Setting;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function calculateDiscount(ApiCalculateDiscountRequest $request) {
        $fleet_route = FleetRoute::find($request->fleet_route_id);
        $setting = Setting::first();
        $price_food = ($request->is_food) ? $fleet_route->fleet_detail?->fleet?->fleetclass?->price_food * $request->seat_count : 0;
        $total_travel = $request->is_travel ? $setting->travel * $request->seat_count : 0;
        $total_member = $request->is_member ? -($setting->member) * $request->seat_count : 0;

        $price_ticket = $request->price_ticket - ($price_food / $request->seat_count) - ($total_travel / $request->seat_count) - ($total_member / $request->seat_count);

        $data = [
            'total_food'=> $price_food,
            'total_travel'=>$total_travel,
            'total_member'=>$total_member
        ];
        $price_with_food = $request->price_ticket * $request->seat_count;
        
        $xendit_charge = function() use ($request, $setting) : int {
            if(empty(UserRepository::findByToken($request->bearerToken())?->agencies)) {
                return $setting->xendit_charge;
            }
            return 0;
        };

        $data = array_merge($data, [
            'price_ticket'  => $price_ticket + $xendit_charge(),
            'total_price'   => $price_with_food + $data['total_travel'] + $data['total_member'] + $data['total_food'] + $xendit_charge(),
            'xendit_charge' => $xendit_charge()
        ]);

        return $this->successResponse($data);
    }
}
