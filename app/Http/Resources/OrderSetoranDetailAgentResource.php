<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderSetoranDetailAgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $table_chairs = $this->getDetailChairs($this, $this->pluck('order_detail'));
        $coll_table_chairs = collect($table_chairs);
        return [
            'fleet_name'=>$this[0]->route?->fleet?->name,
            'chair_count'=>$this->count('order_detail'),
            'commision'=>abs($this->sum("distribution.for_agent")),
            'earning'=>$this->sum('distribution.for_owner'),
            'checkpoint_destination'=>new CheckpointResource($this[0]->route?->checkpoints()->where('agency_id', $this[0]->destination_agency_id)->first()),
            'price_sum'=>$coll_table_chairs->sum('price'),
            'member_count'=>$coll_table_chairs->where('is_member', '==', 'Member')->count(),
            'travel_count'=>$coll_table_chairs->where('is_travel', '==', 'Travel')->count(),
            'food_price_sum'=>$this->sum('distribution.for_food'),
            'member_price_sum'=>$this->sum('distribution.for_member'),
            'travel_price_sum'=>$this->sum('distribution.for_travel'),
            'total_deposit'=>$this->sum('distribution.for_owner')
        ];
    }

    private function getDetailChairs($order, $details) {
        $arr = [];
        foreach($details as $detail) {
            foreach($detail as $order_detail) {
                $arr[] = (object) [
                    'chair_name'=>$order_detail->chair?->name,
                    'price'=>$this->getTruePrice($order_detail->order, $order_detail->order->distribution),
                    'food'=>$order_detail->order->distribution->for_food,
                    'is_member'=>$order_detail->is_member ? "Member" : "Non Member",
                    'is_travel'=>$order_detail->is_travel ? "Travel" : "Non Travel"
                ];
            }
        }
        
        return $arr;
    }

    private function getTruePrice($order, $distribution) {
        return ($order->price - ($distribution->for_food + $distribution->for_travel + $distribution->for_member)) / count($order->order_detail);
    }
}
