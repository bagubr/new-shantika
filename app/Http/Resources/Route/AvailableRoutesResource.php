<?php

namespace App\Http\Resources\Route;

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
        $route = $this->route;
        return [
            'id'                 => $this->id,
            'layout_id'          => $this->fleet->layout->id,
            'route_name'         => $route->name,
            'fleet_name'         => $this->fleet?->name ?? "",
            'fleet_class'        => $this->fleet?->fleetclass->name,
            'departure_at'       => $this->departure_at,
            'arrived_at'         => $this->arrived_at,
            'price'              => $this->price,
            'chairs_available'   => $this->getChairsAvailable($request, $this->fleet_id, $this->id, $this->fleet->layout->id),
            'checkpoints'        => new CheckpointStartEndResource($route)
        ];
    }

    private function getChairsAvailable($request, $fleet_id, $fleet_route_id, $layout_id) {
        $used_count = OrderDetail::whereHas('order', function($query) use ($request, $fleet_id, $fleet_route_id) {
                $query->whereIn('status', Order::STATUS_BOUGHT);
                $query->whereDate('reserve_at', $request->date);
                $query->where('fleet_route_id', $fleet_route_id);
                $query->whereHas('fleet_route', function($subquery) use ($fleet_id) {
                        $subquery->where('fleet_id', $fleet_id);
                });
            })
            ->count();
        $layout = Layout::find($layout_id);
        
        $total_seat = $layout->total_indexes - count($layout->space_indexes) - count($layout->toilet_indexes) - count($layout->door_indexes);

        return $total_seat - $used_count;
    }
}
