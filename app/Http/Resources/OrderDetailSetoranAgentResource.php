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
        $checkpoint_max_index = count($this->route->checkpoints) - 1;
        $table_chairs = $this->getDetailChairs($this, $this->order_detail);
        $coll_table_chairs = collect($table_chairs);
        return [
            'fleet_name'=>$this->route?->fleet?->name,
            'paid_at'=>date('Y-m-d H:i:s', strtotime($this->created_at)),
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
            'chairs'=>$table_chairs,
            'chair_count'=>count($table_chairs),
            'price_sum'=>$coll_table_chairs->sum('price'),
            'commision'=>abs($this->distribution->for_agent),
            'earning'=>$this->price - $this->distribution->for_owner,
            'member_count'=>$coll_table_chairs->where('is_member', '==', 'Member')->count(),
            'travel_count'=>$coll_table_chairs->where('is_travel', '==', 'Travel')->count(),
            'food_price_sum'=>$this->distribution->for_food,
            'member_price_sum'=>$this->distribution->for_member,
            'travel_price_sum'=>$this->distribution->for_travel,
            'total_deposit'=>$this->price - $this->distribution->for_owner
        ];
    }

    private function getDetailChairs($order, $details) {
        $arr = [];
        foreach($details as $detail) {
            $arr[] = (object) [
                'chair_name'=>$detail->chair?->name,
                'price'=>$this->getTruePrice($order, $this->distribution),
                'food'=>$this->distribution->for_food,
                'is_member'=>$detail->is_member ? "Member" : "Non Member",
                'is_travel'=>$detail->is_travel ? "Travel" : "Non Travel"
            ];
        }
        
        return $arr;
    }

    private function getTruePrice($order, $distribution) {
        return ($order->price - ($distribution->for_food + $distribution->for_travel + $distribution->for_member)) / count($order->order_detail);
    }
}
