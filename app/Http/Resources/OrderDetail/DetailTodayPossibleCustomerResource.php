<?php

namespace App\Http\Resources\OrderDetail;

use App\Http\Resources\Checkpoint\CustomerTakenRouteResource;
use App\Http\Resources\CheckpointStartEndResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailTodayPossibleCustomerResource extends JsonResource
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
            'customer_name'=>$this->name,
            'customer_phone'=>$this->phone,
            'where_bought_ticket'=>$this->getWhereTicketBought($this->order),
            'checkpoints'=>new CustomerTakenRouteResource($this->order),
            'price'=>$this->order->distribution->ticket_only,
            'id_member'=>$this->order->id_member,
            'for_member'=>abs($this->order->distribution->for_member),
            'total_price'=>$this->order->price
        ];
    }

    private function getWhereTicketBought($order) {
        if($order->user_id == $order->departure_agency_id) {
            return "Agen ".ucwords(strtolower($order->user?->agencies?->agent?->name));
        }
        return "Aplikasi New Shantika";
    }
}
