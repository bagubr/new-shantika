<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgencyResource extends JsonResource
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
            'agency_id'=>$this->id ?? "",
            'agency_name'=>$this->name ?? "",
            'agency_address'=>$this->address ?? "",
            'agency_phone'=>$this->phone ?? "",
            'agency_lat'=>$this->lat,
            'agency_lng'=>$this->lng,
            'city_name'=>$this->city?->name ?? "",
        ];
    }
}
