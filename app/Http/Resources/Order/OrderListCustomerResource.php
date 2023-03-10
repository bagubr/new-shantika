<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointResource;
use App\Http\Resources\CheckpointStartEndResource;
use App\Repositories\CheckpointRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $fleet_route = $this->fleet_route_with_trash;
        $route = @$fleet_route->route;
        $fleet = @$fleet_route->fleet_detail->fleet;
        $agency_destiny = $this->agency_destiny;
        $agent_start = $this->agency;
        return [
            'id'=>$this->id,
            'code_order'=>$this->code_order,
            'name_fleet'=>@$fleet->name??'Tidak Ditemukan',
            'fleet_class'=>@$fleet->fleetclass?->name??'Tidak Ditemukan',
            'created_at'=>date('Y-m-d H:i:s', strtotime($this->created_at)),
            'departure_at'  => $this->agency->agency_departure_times->where('time_classification_id', $this->time_classification_id)->first()?->departure_at,
            'price'=>$this->distribution?->ticket_only,
            'status'=>$this->status,
            'checkpoints'        => new CheckpointStartEndResource($route, $agency_destiny, $agent_start),
        ];
    }
}
