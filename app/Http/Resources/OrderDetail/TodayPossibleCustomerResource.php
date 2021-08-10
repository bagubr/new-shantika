<?php

namespace App\Http\Resources\OrderDetail;

use Illuminate\Http\Resources\Json\JsonResource;

class TodayPossibleCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order = $this->order;
        return [
            'id'=>$this->id,
            'order_id'=>$this->order_id,
            'name'=>$this->name,
            'phone'=>$this->phone,
            'email'=>$this->email,
            'status'=>$order->status,
            'where_ticket_bought'=>$this->getWhereTicketBought($order)
        ];
    }

    private function getWhereTicketBought($order) {
        if($order->user_id == $order->departure_agency_id) {
            return "Agen ".ucwords(strtolower($order->user?->agencies?->agent?->name));
        }
        return "Aplikasi New Shantika";
    }
}
