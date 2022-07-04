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
            'order_detail_id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'chair_name'=>$this->chair->name,
            'food'=>$this->is_feed ? 1 : 0,
            'is_member'=>$this->is_member,
            'is_travel'=>$this->is_travel,
            'is_feed'=>$this->is_feed,
            "price"=>$this->order->distribution->ticket_only
        ];
    }
}
