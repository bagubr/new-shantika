<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailAgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $checkpoint_max_index = count($this->route->checkpoints) - 1;
        $price_member = $this->getPriceMember($this->order_detail?->pluck('is_member')->toArray() ?? []);
        $price_travel = $this->getPriceTravel($this->order_detail?->pluck('is_travel')->toArray() ?? []);
        $price_feed = $this->getPriceFeed($this->order_detail?->pluck('is_feed')->toArray() ?? []);
        return [
            'id'=>$this->id,
            'code_order'=>$this->code_order,
            'name_fleet'=>$this->route?->fleet?->name,
            'fleet_class'=>$this->route?->fleet?->fleetclass?->name,
            'total_passenger'=>count($this->order_detail ?? []),
            'checkpoints'        => (object) [
                'start' => (object) [
                    'agency_id'=>$this->route->checkpoints[0]?->agency?->id ?? "",
                    'agency_name'=>$this->route->checkpoints[0]?->agency?->name ?? "",
                    'city_name'=>$this->route->checkpoints[0]?->agency?->city?->name ?? "",
                    'arrived_at'=>$this->route->checkpoints[0]?->arrived_at,
                ],
                'end'   => (object) [
                    'agency_id'=>$this->route->checkpoints[$checkpoint_max_index]?->agency?->id ?? "",
                    'agency_name'=>$this->route->checkpoints[$checkpoint_max_index]?->agency?->name ?? "",
                    'city_name'=>$this->route->checkpoints[$checkpoint_max_index]?->agency?->city?->name ?? "",
                    'arrived_at'=>$this->route->checkpoints[$checkpoint_max_index]?->arrived_at,
                ]
            ],
            'created_at'=>date('Y-m-d H:i:s', strtotime($this->created_at)),
            'reserve_at'=>date('Y-m-d H:i:s', strtotime($this->reserve_at)),
            'status'=>$this->status,
            'name_passenger'=>@$this->order_detail[0]->name ?? "",
            'phone_passenger'=>@$this->order_detail[0]->phone ?? "",
            'seat_passenger'=>$this->order_detail?->pluck('chair.name'),
            'price_member'=>$price_member,
            'price_travel'=>$price_travel,
            'price_feed'=>$price_feed,
            'id_member'=>$this->id_member,
            'price'=>$this->price,
            'total_price'=>$this->price + $price_travel + $price_feed - $price_member,
            'commision'=>$this->getCommision()
        ];
    }

    private function getPriceFeed($is_feed = [])
    {
        $price = 0;
        foreach ($is_feed as $key => $value) {
            if($value == true){
                $price += config('application.price_list.feed');
            }
        }
        return $price;
    }
    
    private function getPriceTravel($is_travel = [])
    {
        $price = 0;
        foreach ($is_travel as $key => $value) {
            if($value == true){
                $price += config('application.price_list.travel');
            }
        }
        return $price;
    }

    private function getPriceMember($is_member = [])
    {
        $price = 0;
        foreach ($is_member as $key => $value) {
            if($value == true){
                $price += config('application.price_list.member');
            }
        }
        return $price;
    }

    private function getCommision() {
        return round(config('application.commision') * $this->price);
    }
}
