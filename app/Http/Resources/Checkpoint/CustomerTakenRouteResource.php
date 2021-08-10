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
            'fleet_name'=>$this->route?->fleet?->name,
            'fleet_class'=>$this->route?->fleet?->fleetclass?->name,
            'checkpoints'=>new CheckpointStartEndResource($this->route),
            'checkpoint_destination'=>new CheckpointResource($this->route->checkpoints()->where('agency_id', $this->destination_agency_id)->first()),
            'status'=>$this->status
        ];
    }
}
