<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointStartEndResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailCustomerResource extends JsonResource
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
            'total_passenger'=>count($this->order_detail),
            'checkpoints'        => new CheckpointStartEndResource($this->route),
            'created_at'=>date('Y-m-d H:i:s', strtotime($this->created_at)),
            'reserve_at'=>date('Y-m-d H:i:s', strtotime($this->reserve_at)),
            'departure_at'=>$this->route->departure_at,
            'status'=>$this->status,
            'name_passenger'=>$this->order_detail[0]->name,
            'phone_passenger'=>$this->order_detail[0]->phone,
            'seat_passenger'=>$this->order_detail->pluck('chair.name'),
            'price_member'=>$this->getPriceMember($this->order_detail->pluck('is_member')->toArray()),
            'price_travel'=>$this->getPriceTravel($this->order_detail->pluck('is_travel')->toArray()),
            'price_feed'=>$this->getPriceFeed($this->order_detail->pluck('is_feed')->toArray()),
            'id_member'=>$this->id_member,
            'payment_type'=>$this->payment?->payment_type?->name,
            'price'=>$this->price,
        ];
    }

    public function getPriceFeed(array $is_feed)
    {
        $price = 0;
        foreach ($is_feed as $key => $value) {
            if($value == true){
                $price += config('application.price_list.feed');
            }
        }
        return $price;
    }
    
    public function getPriceTravel(array $is_travel)
    {
        $price = 0;
        foreach ($is_travel as $key => $value) {
            if($value == true){
                $price += config('application.price_list.travel');
            }
        }
        return $price;
    }

    public function getPriceMember(array $is_member)
    {
        $price = 0;
        foreach ($is_member as $key => $value) {
            if($value == true){
                $price += config('application.price_list.member');
            }
        }
        return $price;
    }
}
