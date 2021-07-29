<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderListSetoranAgentResource extends JsonResource
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
        return [
            'id'=>$this->id,
            'fleet_name'=>$this->route?->fleet?->name,
            'chairs'=>$this->order_detail()->count(),
            'deposit'=>abs($this->distribution()->sum('for_owner')),
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
        ];
    }
}
