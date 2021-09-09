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
        $this->agency = $this->load(['agency', 'agency_destiny'])->agency;
        $agent_start = $this->agency;
        return [
            'fleet_name'=>$this->fleet_route?->fleet_detail?->fleet?->name,
            'fleet_class'=>$this->fleet_route?->fleet_detail?->fleet?->fleetclass?->name,
            'checkpoints'=>new CheckpointStartEndResource($this->fleet_route?->route, $this->agency_destiny, $agent_start),
            'status'=>$this->status
        ];
    }
}
