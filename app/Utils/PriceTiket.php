<?php

namespace App\Utils;

use App\Models\Agency;
use App\Models\FleetRoute;

class PriceTiket {
    public static function priceTiket(FleetRoute $fleet_route, Agency $departure_agency, Agency $agency_destiny, $date) {
        $price = 0;
        if($departure_agency->city->area_id == 1){
            $price += $fleet_route->fleet_detail->fleet->fleetclass->price_fleet_class1;
            if($price <= 0){
                if($agency_destiny->is_agent == true){
                    $price += $agency_destiny->agency_prices->sortByDesc('start_at')->first()->price;
                }else{
                    $price += $agency_destiny->route_prices->sortByDesc('start_at')->first()->price;
                }
                // $price += $fleet_route->fleet_detail->fleet->fleetclass->price_fleet_class1;
            }
        }elseif($departure_agency->city->area_id == 2) {
            $price += $fleet_route->fleet_detail->fleet->fleetclass->price_fleet_class2;
            if($price <= 0){
                if($departure_agency->is_agent == true){
                    $price += $departure_agency->agency_prices->sortByDesc('start_at')->first()->price;
                }else{
                    $price += $departure_agency->route_prices->sortByDesc('start_at')->first()->price;
                }
                // $price += $fleet_route->fleet_detail->fleet->fleetclass->price_fleet_class2;
            }
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