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
        $order = $this->order;
        $distribution = $order->distribution;
        return [
            'customer_name'=>$this->name,
            'customer_phone'=>$this->phone,
            'customer_email'=>$this->email,
            'reserve_at'=>$order->reserve_at,
            'chairs'=>$order->order_detail()->with('chair')->get()->pluck('chair.name')->values(),
            'where_bought_ticket'=>$this->getWhereTicketBought($order),
            'checkpoints'=>new CustomerTakenRouteResource($order),
            'price'=>$distribution->ticket_only,
            'id_member'=>$order->id_member,
            'travel'=>$distribution->for_travel,
            'food'=>$distribution->for_food,
            'member'=>$distribution->for_member,
            'id_member'=>$order->id_member,
            'total_price'=>$order->price
        ];
    }

    private function getWhereTicketBought($order) {
        if($order->user_id == $order->departure_agency_id) {
            return "Agen ".ucwords(strtolower($order->user?->agencies?->agent?->name));
        }
        return "Aplikasi New Shantika";
    }
}
