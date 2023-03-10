<?php

namespace App\Http\Resources\Route;

use App\Http\Resources\CheckpointStartEndResource;
use App\Models\Agency;
use App\Models\BlockedChair;
use App\Models\FleetRoute;
use App\Models\Order;
use App\Models\Layout;
use App\Models\OrderDetail;
use App\Models\TimeChangeRoute;
use App\Repositories\AgencyRepository;
use App\Repositories\UserRepository;
use App\Utils\PriceTiket;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailableRoutesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $departure_agency = Agency::find($request->agency_departure_id) ?? AgencyRepository::findByToken($request->bearerToken());
        $destination_agency_id = $request->agency_arrived_id ?? $request->agency_id;
        $agency_destiny = Agency::find($destination_agency_id); 
        $route = $this->route;
        
        $price = PriceTiket::priceTiket(FleetRoute::find($this->id), $departure_agency, $agency_destiny, $request->date);
        // if(UserRepository::findByToken(request()->bearerToken())->agencies){
        //     $price += $this->fleet_detail->fleet->fleetclass->price_food??0;
        // }
        return [
            'id'                        => $this->id,
            'layout_id'                 => $this->fleet_detail->fleet->layout->id,
            'route_name'                => $route->name,
            'fleet_name'                => $this->fleet_detail->fleet->name ?? "",
            'fleet_detail_time'         => $this->fleet_detail->time_classification->name ?? "",
            'fleet_class'               => $this->fleet_detail->fleet->fleetclass->name,
            'departure_at'              => $agency_destiny->agency_departure_times->first()->departure_at,
            'price'                     => $price,
            'agency_id'                 => $departure_agency->id,
            'chairs_available'          => $this->getChairsAvailable($request, $this->fleet_detail_id, $this->id, $this->fleet_detail->fleet->layout_id),
            'checkpoints'               => $this->when(count($route->checkpoints) >= 1, new CheckpointStartEndResource($route, $agency_destiny, $departure_agency)),
        ];
    }

    private function getChairsAvailable($request, $fleet_detail_id, $fleet_route_id, $layout_id) {
        $used_count = OrderDetail::whereHas('order', function($query) use ($request, $fleet_detail_id, $fleet_route_id) {
                $query->whereIn('status', Order::STATUS_BOUGHT);
                $query->whereDate('reserve_at', $request->date);
                $query->where('fleet_route_id', $fleet_route_id);
                $query->whereHas('fleet_route', function($subquery) use ($fleet_detail_id) {
                    $subquery->where('fleet_detail_id', $fleet_detail_id);
                });
            })
            ->count();
        // $booking_count = Booking::where('fleet_route_id', $fleet_route_id)->where('expired_at', '>=', date('Y-m-d H:i:s'))->count();
        $blocked_count = BlockedChair::where('fleet_route_id', $fleet_route_id)->count();
        $layout = Layout::find($layout_id);
        
        $total_seat = $layout->total_indexes - count($layout->space_indexes) - count($layout->toilet_indexes) - count($layout->door_indexes);

        return $total_seat - ($used_count + $blocked_count);
    }
}
