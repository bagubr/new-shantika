<?php

namespace App\Http\Resources;

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
            'price'=>$this->getTruePrice($this->order, $this->order->distribution),
            'food'=>$this->is_feed ? $this->order->distribution->for_food / $this->order->order_detail->where('is_feed', true)->count() : 0,
            'is_member'=>$this->is_member ? "Member" : "Non Member",
            'is_travel'=>$this->is_travel ? "Travel" : "Non Travel"
        ];
    }

    private function getTruePrice($order, $distribution) {
        return ($order->price - ($distribution->for_food + $distribution->for_travel + $distribution->for_member)) / count($order->order_detail);
    }
}
