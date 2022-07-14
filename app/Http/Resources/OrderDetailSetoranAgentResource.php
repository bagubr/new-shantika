<?php

namespace App\Http\Resources;

use App\Utils\FoodPrice;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailSetoranAgentResource extends JsonResource
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
            'chair_name'=>$this->chair->name,
            'price'=>$this->order->distribution->ticket_price / $this->order->order_detail->count(),
            'food'=> FoodPrice::foodPrice($this->order->fleet_route, false, 1),
            'is_member'=>$this->is_member ? "Member" : "Non Member",
            'is_travel'=>$this->is_travel ? "Travel" : "Non Travel",
            'customer_name'=>$this->name,
            'customer_phone'=>$this->phone,
            'customer_email'=>$this->email,
            'is_self_bought'=>!$this->order?->user?->agencies
        ];
    }

    private function getTruePrice($order, $distribution) {
        return $distribution->ticket_only / count($order->order_detail);
    }
}
