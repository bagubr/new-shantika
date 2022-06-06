<?php

namespace App\Utils;

use App\Models\Agency;
use App\Models\FleetRoute;

class PriceTiket {
    public static function priceTiket(FleetRoute $fleet_route, Agency $departure_agency, Agency $agency_destiny, $date) {
        $price = 0;
        $area_id = $departure_agency->city->area_id;

            $price += $fleet_route->fleet_detail->fleet->fleetclass->price_fleet_class($area_id)??0;
            if($area_id == 1){
                $price += @$agency_destiny->route_prices->sortByDesc('start_at')->first()->price??0;
            }elseif($area_id == 2){
                $price += @$departure_agency->agency_prices->sortByDesc('start_at')->first()->price??0;
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