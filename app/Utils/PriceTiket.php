<?php

namespace App\Utils;

use App\Models\Agency;
use App\Models\FleetRoute;
use App\Repositories\UserRepository;

class PriceTiket {
    public static function priceTiket(FleetRoute $fleet_route, Agency $departure_agency, Agency $agency_destiny, $date) {
        $price = 0;
        $area_id = $departure_agency->city->area_id;
            $user = UserRepository::findByToken(request()->bearerToken());
            
            if($area_id == 1){
                $price = @$agency_destiny->route_prices->sortByDesc('start_at')->last()->price??0;
            }elseif($area_id == 2){
                $price = @$departure_agency->agency_prices->sortByDesc('start_at')->last()->price??0;
            }
            if($price <= 0){
                $price += $fleet_route->fleet_detail->fleet->fleetclass->price_fleet_class($area_id)??0;
            }
            if(@$user->agencies){
                $price -= $fleet_route->fleet_detail->fleet->fleetclass->price_food??0;
            }

        $price += @$fleet_route->prices()
            ->whereDate('start_at', '<=', $date)
            ->whereDate('end_at', '>=', $date)
            ->orderBy('created_at', 'desc')
            ->first()
            ->true_deviation_price??0;

        return $price;
    }   
}