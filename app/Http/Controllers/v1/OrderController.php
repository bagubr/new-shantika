<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiCalculateDiscountRequest;
use App\Models\Agency;
use App\Models\FleetRoute;
use App\Models\Route;
use App\Models\Setting;
use App\Repositories\UserRepository;
use App\Utils\PriceTiket;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function calculateDiscount(ApiCalculateDiscountRequest $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        $fleet_route = FleetRoute::find($request->fleet_route_id);
        $departure_agency = Agency::find($request->agency_departure_id);
        $agency_destiny = Agency::find($request->agency_destiny_id);
        $date = $request->date;
        $setting = Setting::first();
        $price_food = ($request->is_food) ? $fleet_route->fleet_detail?->fleet?->fleetclass?->price_food * $request->seat_count : 0;
        $total_travel = $request->is_travel ? $setting->travel * $request->seat_count : 0;
        $total_member = $request->is_member ? -($setting->member) * $request->seat_count : 0;

        $price_ticket = PriceTiket::priceTiket($fleet_route, $departure_agency, $agency_destiny, $date);

        // $price_food = @$user?->agencies?->agent?->city?->area_id == 2 
        // ? 0
        // : $price_food;

        
        $data = [
            'total_food'=> $price_food,
            'total_travel'=>$total_travel,
            'total_member'=>$total_member
        ];
        $price_with_food = $price_ticket * $request->seat_count;

        if(@$user->agencies){
            $price_with_food += $price_food;
        }
        
        $xendit_charge = function() use ($setting, $user) : int {
            if(empty($user?->agencies)) {
                return $setting->xendit_charge;
            }
            return 0;
        };

        $data = array_merge($data, [
            'total_price'   => $price_with_food + $data['total_travel'] + $data['total_member'] + $xendit_charge(),
            'xendit_charge' => $xendit_charge()
        ]);

        return $this->successResponse($data);
    }
}
