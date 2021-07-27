<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTiketResource extends JsonResource
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
            'id' => $this->id,
            'code_order' => $this->code_order,
            'name_fleet' => $this->route->fleet->name,
            'name_passenger'=>$this->order_detail[0]->name,
            'seat_passenger'=>$this->order_detail->pluck('chair.name'),
            'created_at'=>date('Y-m-d H:i:s', strtotime($this->created_at)),
            'reserve_at'=>date('Y-m-d H:i:s', strtotime($this->reserve_at)),
            'checkpoints'        => (object) [
                'start' => (object) [
                    'agency_id'=>$this->route->checkpoints[0]?->agency?->id ?? "",
                    'agency_name'=>$this->route->checkpoints[0]?->agency?->name ?? "",
                    'city_name'=>$this->route->checkpoints[0]?->agency?->city?->name ?? "",
                    'arrived_at'=>$this->route->checkpoints[0]?->arrived_at,
                    'agency_address'=>$this->route->checkpoints[0]?->agency?->address ?? "",
                    'agency_phone'=>$this->route->checkpoints[0]?->agency?->phone ?? "",
                ],
                'end'   => (object) [
                    'agency_id'=>$this->route->checkpoints[$checkpoint_max_index]?->agency?->id ?? "",
                    'agency_name'=>$this->route->checkpoints[$checkpoint_max_index]?->agency?->name ?? "",
                    'city_name'=>$this->route->checkpoints[$checkpoint_max_index]?->agency?->city?->name ?? "",
                    'arrived_at'=>$this->route->checkpoints[$checkpoint_max_index]?->arrived_at,
                    'agency_address'=>$this->route->checkpoints[$checkpoint_max_index]?->agency?->address ?? "",
                    'agency_phone'=>$this->route->checkpoints[$checkpoint_max_index]?->agency?->phone ?? "",
                ]
            ],
        ];
    }
}
