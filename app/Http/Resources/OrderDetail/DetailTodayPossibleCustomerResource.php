<?php

namespace App\Http\Resources\OrderDetail;

use App\Http\Resources\Checkpoint\CustomerTakenRouteResource;
use App\Http\Resources\CheckpointStartEndResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
            'customer_name'=>$order->order_detail[0]->name,
            'customer_phone'=>$order->order_detail[0]->phone,
            'customer_email'=>$order->order_detail[0]->email,
            'reserve_at'=>$order->reserve_at,
            'departure_at'=>$order->agency->agency_departure_times->where('time_classification_id', $this->order->time_classification_id)->first()->departure_at,
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
