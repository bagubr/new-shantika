<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointStartEndResource;
use App\Http\Resources\OrderDetailChairResource;
use App\Http\Resources\OrderDetailSetoranAgentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailAgentResource extends JsonResource
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
        $price_feed = $this->distribution->for_food;
        $price_travel = $this->distribution->for_travel;
        $price_member = $this->distribution->for_member;
        return [
            'id'=>$this->id,
            'code_order'=>$this->code_order,
            'name_fleet'=>$this->route?->fleet?->name,
            'fleet_class'=>$this->route?->fleet?->fleetclass?->name,
            'total_passenger'=>count($this->order_detail ?? []),
            'checkpoints'        => new CheckpointStartEndResource($this->route),
            'created_at'=>date('Y-m-d H:i:s', strtotime($this->created_at)),
            'reserve_at'=>date('Y-m-d H:i:s', strtotime($this->reserve_at)),
            'departure_at'=>$this->route->departure_at,
            'status'=>$this->status,
            'name_passenger'=>@$this->order_detail[0]->name ?? "",
            'phone_passenger'=>@$this->order_detail[0]->phone ?? "",
            'seat_passenger'=>$this->order_detail?->pluck('chair.name'),
            'chairs'=>OrderDetailChairResource::collection($this->order_detail),
            'price_member'=>abs($price_member),
            'price_travel'=>$price_travel,
            'price_feed'=>$price_feed,
            'id_member'=>$this->id_member,
            'price'=>$this->distribution->ticket_only,
            'total_price'=>$this->price,
            'commision'=>$this->distribution->for_agent
        ];
    }
}
