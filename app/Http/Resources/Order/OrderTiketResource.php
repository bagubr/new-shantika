<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointStartEndResource;
use App\Repositories\CheckpointRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderTiketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $route = $this->fleet_route->route;
        $checkpoint_destination = CheckpointRepository::findByRouteAndAgency($route?->id, $this->destination_agency_id);
        $agent_start = $this->agency;
        return [
            'id' => $this->id,
            'code_order' => $this->code_order,
            'name_fleet' => $this->fleet_route->fleet_detail->fleet->name,
            'name_passenger'=>$this->order_detail[0]->name,
            'seat_passenger'=>$this->order_detail->pluck('chair.name'),
            'created_at'=>date('Y-m-d H:i:s', strtotime($this->created_at)),
            'reserve_at'=>date('Y-m-d H:i:s', strtotime($this->reserve_at)),
            'departure_at'  => $this->agency->agency_departure_times->where('time_classification_id', $this->time_classification_id)->first()->departure_at,
            'checkpoints'        => new CheckpointStartEndResource($route, $checkpoint_destination, $agent_start),
        ];
    }
}
