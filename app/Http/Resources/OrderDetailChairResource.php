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
            "price"=>$this->order->distribution->ticket_only
        ];
    }
}
