<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckpointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'agency_id'=>$this->agency?->id ?? "",
            'agency_name'=>$this->agency?->name ?? "",
            'agency_address'=>$this->agency?->address ?? "",
            'agency_phone'=>$this->agency?->phone ?? "",
            'agency_lat'=>$this->agency?->lat,
            'agency_lng'=>$this->agency?->lng,
            'city_name'=>$this->agency?->city?->name ?? "",
        ];
    }
}
