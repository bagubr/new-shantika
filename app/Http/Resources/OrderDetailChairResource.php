<?php

namespace App\Http\Resources;

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
        return [
            'chair_name'=>$this->chair->name,
            'food'=>$this->is_feed ? 1 : 0,
            'is_member'=>$this->is_member ? "Member" : "Non Member",
            'is_travel'=>$this->is_travel ? "Travel" : "Non Travel",
            "price"=>$this->getTruePrice($this->order?->distribution, $this->is_food, $this->is_member, $this->is_travel)
        ];
    }

    private function getTruePrice($distribution, $is_food, $is_member, $is_travel) {
        $order_detail_count = count($this->order?->order_detail);
        $food = $distribution->for_food / $order_detail_count;
        $member = $distribution->for_member / $order_detail_count;
        $travel = $distribution->for_travel / $order_detail_count;

        return ($distribution->ticket_only / $order_detail_count) + $food + $member + $travel;
    }
}
