<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointStartEndResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListAgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $checkpoint_max_index = count($this->route->checkpoints) - 1;
        return [
            'id'=>$this->id,
            'code'=>$this->code,
            'name_fleet'=>$this->route?->fleet?->name,
            'fleet_class'=>$this->route?->fleet?->fleetclass?->name,
            'reserve_at'=>$this->reserve_at,
            'status'=>$this->status,
            'type'=>$this->type,
            'checkpoints'        => new CheckpointStartEndResource($this->route),
        ];
    }
}
