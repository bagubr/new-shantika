<?php

namespace App\Http\Resources\Route;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AvailableRoutesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $checkpoint_max_index = count($this->checkpoints) - 1;
        return [
            'id'                 => $this->id,
            'route_name'         => $this->name,
            'fleet_name'         => $this->fleet?->name ?? "",
            'departure_at'       => $this->departure_at,
            'arrived_at'         => $this->arrived_at,
            'fleet_class'        => $this->fleet_class,
            'price'              => $this->price,
            'chairs_available'   => rand(1, 32),
            'checkpoints'        => (object) [
                'start' => (object) [
                    'agency_id'=>$this->checkpoints[0]?->agency?->id ?? "",
                    'agency_name'=>$this->checkpoints[0]?->agency?->name ?? "",
                    'city_name'=>$this->checkpoints[0]?->agency?->city?->name ?? "",
                    'arrived_at'=>$this->checkpoints[0]?->arrived_at,
                ],
                'end'   => (object) [
                    'agency_id'=>$this->checkpoints[$checkpoint_max_index]?->agency?->id ?? "",
                    'agency_name'=>$this->checkpoints[$checkpoint_max_index]?->agency?->name ?? "",
                    'city_name'=>$this->checkpoints[$checkpoint_max_index]?->agency?->city?->name ?? "",
                    'arrived_at'=>$this->checkpoints[$checkpoint_max_index]?->arrived_at,
                ]
            ],
        ];
    }
}
