<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointStartEndResource;
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
        $checkpoint_max_index = count($this->route->checkpoints) - 1;
        return [
            'id'=>$this->id,
            'code_order'=>$this->code_order,
            'name_fleet'=>$this->route?->fleet?->name,
            'fleet_class'=>$this->route?->fleet?->fleetclass?->name,
            'created_at'=>date('Y-m-d H:i:s', strtotime($this->created_at)),
            'price'=>$this->price,
            'status'=>$this->status,
            'checkpoints'        => new CheckpointStartEndResource($this->route),
        ];
    }
}
