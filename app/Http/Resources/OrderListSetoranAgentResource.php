<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderListSetoranAgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $checkpoint_max_index = count($this->fleet_route->route->checkpoints) - 1;
        return [
            'id'=>$this->id,
            'fleet_name'=>$this->fleet_route?->fleet?->name,
            'fleet_id'=>$this->fleet_route?->fleet?->id,
            'chairs_count'=>$this->order_detail()->count(),
            'deposit'=>abs($this->distribution()->sum('for_owner')),
            'checkpoints'=> new CheckpointStartEndResource($this->fleet_route)
        ];
    }
}
