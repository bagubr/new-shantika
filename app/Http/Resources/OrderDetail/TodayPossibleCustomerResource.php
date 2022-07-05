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
        $order = $this->order->load('agency.agency_departure_times');
        $distribution = $order->distribution;
        return [
            'id'=>$this->id,
            'order_id'=>$this->order_id,
            'departure_at'=>$order->agency?->agency_departure_times?->where('time_classification_id', $this->order->time_classification_id)->first()?->departure_at??'',
            'name'=>$this->name,
            'phone'=>$this->phone,
            'email'=>$this->email,
            'fleet_name'=>$order->fleet_route?->fleet_detail?->fleet?->name,
            'fleet_class'=>$order->fleet_route?->fleet_detail?->fleet?->fleetclass?->name,
            'chair_name'=>$this->chair->name,
            'status'=>$order->status,
            'travel'=>$distribution->for_travel,
            'food'=>$distribution->for_food,
            'member'=>$distribution->for_member,
            'id_member'=>$order->id_member,
            'where_ticket_bought'=>$this->getWhereTicketBought($order),
            'note'=>$this->order->note,
        ];
    }

    private function getWhereTicketBought($order) {
        if($order->user_id == $order->departure_agency_id) {
            return "Agen ".ucwords(strtolower($order->user?->agencies?->agent?->name));
        }
        return "Aplikasi New Shantika";
    }
}
