<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointStartEndResource;
use App\Models\Booking;
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
            'layout_chair_id'=> Booking::where('code_booking', $this->code)->pluck('layout_chair_id') ?? $this->order_detail?->pluck('layout_chair_id'),
            'route_id'=>$this->route?->id,
            'code'=>$this->code,
            'name_fleet'=>$this->route?->fleet?->name,
            'fleet_class'=>$this->route?->fleet?->fleetclass?->name,
            'departure_at'=>$this->route?->departure_at,
            'is_feed'=>$this->order_detail()->where('is_feed', true)->exists(),
            'price'=>$this->price,
            'reserve_at'=>$this->reserve_at,
            'status'=>$this->status,
            'type'=>$this->type,
            'checkpoints'        => new CheckpointStartEndResource($this->route),
        ];
    }
}
