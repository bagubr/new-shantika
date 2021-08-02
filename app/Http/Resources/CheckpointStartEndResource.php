<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckpointStartEndResource extends JsonResource
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
            'start' => (object) [
                'agency_id'=>$this->checkpoints[0]?->agency?->id ?? "",
                'agency_name'=>$this->checkpoints[0]?->agency?->name ?? "",
                'agency_address'=>$this->checkpoints[0]?->agency?->address ?? "",
                'agency_phone'=>$this->checkpoints[0]?->agency?->phone ?? "",
                'agency_lat'=>$this->checkpoints[0]?->agency?->lat,
                'agency_lng'=>$this->checkpoints[0]?->agency?->lng,
                'city_name'=>$this->checkpoints[0]?->agency?->city?->name ?? "",
                'arrived_at'=>$this->checkpoints[0]?->arrived_at,
            ],
            'end'   => (object) [
                'agency_id'=>$this->checkpoints[$checkpoint_max_index]?->agency?->id ?? "",
                'agency_name'=>$this->checkpoints[$checkpoint_max_index]?->agency?->name ?? "",
                'agency_address'=>$this->checkpoints[$checkpoint_max_index]?->agency?->address ?? "",
                'agency_phone'=>$this->checkpoints[$checkpoint_max_index]?->agency?->phone ?? "",
                'agency_lat'=>$this->checkpoints[$checkpoint_max_index]?->agency?->lat,
                'agency_lng'=>$this->checkpoints[$checkpoint_max_index]?->agency?->lng,
                'city_name'=>$this->checkpoints[$checkpoint_max_index]?->agency?->city?->name ?? "",
                'arrived_at'=>$this->checkpoints[$checkpoint_max_index]?->arrived_at,
            ]
        ];
    }
}
