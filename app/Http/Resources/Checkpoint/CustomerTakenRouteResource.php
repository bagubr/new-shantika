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
        $this->agency = $this->load('agency')->agency;
        $checkpoint_destination = $this->fleet_route?->route->checkpoints()->where('agency_id', $this->destination_agency_id)->first();
        $agent_start = $this->agency;
        return [
            'fleet_name'=>$this->fleet_route?->fleet_detail?->fleet?->name,
            'fleet_class'=>$this->fleet_route?->fleet_detail?->fleet?->fleetclass?->name,
            'checkpoints'=>new CheckpointStartEndResource($this->fleet_route?->route, $checkpoint_destination, $agent_start),
            'status'=>$this->status
        ];
    }
}
