<?php

namespace App\Utils;

use App\Models\Agency;
use App\Models\FleetRoute;
<<<<<<< HEAD
=======
use App\Repositories\UserRepository;
>>>>>>> rilisv1

class PriceTiket {
    public static function priceTiket(FleetRoute $fleet_route, Agency $departure_agency, Agency $agency_destiny, $date) {
        $price = 0;
<<<<<<< HEAD
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
=======
        $area_id = $departure_agency->city->area_id;
            $user = UserRepository::findByToken(request()->bearerToken());
            
            $price += $fleet_route->fleet_detail->fleet->fleetclass->price_fleet_class($area_id)??0;
            if(@$user->agencies){
                $price -= $fleet_route->fleet_detail->fleet->fleetclass->price_food??0;
            }
            if($area_id == 1){
                $price += @$agency_destiny->route_prices->sortByDesc('start_at')->last()->price??0;
            }elseif($area_id == 2){
                $price += @$departure_agency->agency_prices->sortByDesc('start_at')->last()->price??0;
            }
>>>>>>> rilisv1

        $price += @$fleet_route->prices()
            ->whereDate('start_at', '<=', $date)
            ->whereDate('end_at', '>=', $date)
            ->orderBy('created_at', 'desc')
            ->first()
            ->true_deviation_price??0;

        return $price;
    }   
}