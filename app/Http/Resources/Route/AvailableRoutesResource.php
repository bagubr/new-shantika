<?php

namespace App\Http\Resources\Route;

use App\Http\Resources\CheckpointStartEndResource;
use App\Models\Fleet;
use App\Models\LayoutChair;
use App\Models\Order;
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
        return [
            'id'                 => $this->id,
            'layout_id'          => $this->fleet->layout->id,
            'route_name'         => $this->name,
            'fleet_name'         => $this->fleet?->name ?? "",
            'departure_at'       => $this->departure_at,
            'arrived_at'         => $this->arrived_at,
            'fleet_class'        => $this->fleet_class,
            'price'              => $this->price,
            'chairs_available'   => $this->getChairsAvailable($request, $this->id),
            'checkpoints'        => new CheckpointStartEndResource($this)
        ];
    }

    private function getChairsAvailable($request, $fleet_id) {
        $used_count = OrderDetail::whereHas('order', function($query) use ($request, $fleet_id) {
                $query->where('status', Order::STATUS_BOUGHT)
                    ->where('reserve_at', $request->date)
                    ->whereHas('route', function($subquery) use ($fleet_id) {
                        $subquery->where('fleet_id', $fleet_id);
                    });
            })
            ->count();
        $total_seat = LayoutChair::whereHas('layout.fleet', function($query) use ($fleet_id) {
            $query->where('id', $fleet_id);
        })->count();

        return $total_seat - $used_count;
    }
}
