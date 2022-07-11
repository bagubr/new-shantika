<?php

namespace App\Utils;

use App\Models\Agency;
use App\Models\FleetRoute;
use App\Models\Setting;
use App\Repositories\UserRepository;

class FoodPrice {
    public static function foodPrice(FleetRoute $fleet_route, $is_food = false, $seat_count = 1) {
        if($is_food){
            return $fleet_route->fleet_detail?->fleet?->fleetclass?->price_food * $seat_count;
        }else{
            return - Setting::first()->default_food_price * $seat_count;
        }
    }   
}