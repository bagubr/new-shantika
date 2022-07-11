<?php

namespace App\Http\Resources;

use App\Models\Setting;
use App\Utils\FoodPrice;
use Google\Service\ContainerAnalysis\Distribution;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailChairResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $price = $this->order->distribution->ticket_price - (($this->is_member == 1)?Setting::first()->member:0) + (($this->is_travel == 1)?Setting::first()->travel:0) + FoodPrice::foodPrice($this->order->fleet_route, $this->is_feed);
        return [
            'order_detail_id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'chair_name'=>$this->chair->name,
            'food'=>$this->is_feed ? 1 : 0,
            'is_member'=>$this->is_member,
            'is_travel'=>$this->is_travel,
            'is_feed'=>$this->is_feed,
            'price_member'=>$this->price_member,
            'price_feed'=>FoodPrice::foodPrice($this->order->fleet_route, $this->is_feed),
            'price'=> $price,
        ];
    }
}
