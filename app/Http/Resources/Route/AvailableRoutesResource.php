<?php

namespace App\Http\Resources\Route;

use App\Http\Resources\CheckpointResource;
use App\Http\Resources\CheckpointStartEndResource;
use App\Models\Fleet;
use App\Models\LayoutChair;
use App\Models\Order;
use App\Models\Layout;
use App\Models\OrderDetail;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
        $destination_agency_id = $request->agency_arrived_id ?? $request->agency_id;

        $route = $this->route;
        $checkpoints = $this->route?->checkpoints();
        $first_checkpoint = $checkpoints->orderBy('order', 'asc')->first();
        $last_checkpoint = $checkpoints->orderBy('order', 'desc')->first();
        $destination_checkpoint = $checkpoints->where('agency_id', $destination_agency_id)->first();
        return [
            'id'                        => $this->id,
            'layout_id'                 => $this->fleet_detail->fleet->layout->id,
            'route_name'                => $route->name,
            'fleet_name'                => $this->fleet_detail->fleet->name ?? "",
            'fleet_class'               => $this->fleet_detail->fleet->fleetclass->name,
            'departure_at'              => $first_checkpoint->agency->departure_at,
            'price'                     => $this->price,
            'chairs_available'          => $this->getChairsAvailable($request, $this->fleet_detail_id, $this->id, $this->fleet_detail->fleet->layout_id),
            'checkpoints'               => $this->when(@count($route->checkpoints) > 1, new CheckpointStartEndResource($route, $destination_checkpoint, (object) [])),
            'city_start'                => $first_checkpoint->agency->city_name,
            'city_end'                  => $last_checkpoint->agency->city_name
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
        $layout = Layout::find($layout_id);
        
        $total_seat = $layout->total_indexes - count($layout->space_indexes) - count($layout->toilet_indexes) - count($layout->door_indexes);

        return $total_seat - $used_count;
    }
}
