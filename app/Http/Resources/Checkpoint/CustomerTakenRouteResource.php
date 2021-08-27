<?php

namespace App\Http\Resources\Checkpoint;

use App\Http\Resources\CheckpointResource;
use App\Http\Resources\CheckpointStartEndResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerTakenRouteResource extends JsonResource
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
            'fleet_name'=>$this->fleet_route?->fleet?->name,
            'fleet_class'=>$this->fleet_route?->fleet?->fleetclass?->name,
            'checkpoints'=>new CheckpointStartEndResource($this->fleet_route?->route, $this->fleet_route?->route->checkpoints()->where('agency_id', $this->destination_agency_id)->first()),
            'city_start'                => $this->fleet_route?->route?->departure_city?->name,
            'city_end'                  => $this->fleet_route?->route?->destination_city?->name,
            'status'=>$this->status
        ];
    }
}
