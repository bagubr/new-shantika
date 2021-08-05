<?php

namespace App\Http\Resources\Agency;

use Illuminate\Http\Resources\Json\JsonResource;

class AgencyWithAddressTelpResource extends JsonResource
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
            'id'=>$this->id ?? "",
            'agency_name'=>$this->name ?? "",
            'city_name'=>$this->city?->name ?? "",
            'agency_address'=>$this->address ?? "",
            'agency_phone'=>$this->phone ?? "",
            'phone'=>$this->users->pluck('phone') ?? "",
            'agency_avatar'=>$this->avatar_url,
            'agency_lat'=> $this->lat,
            'agency_lng'=> $this->lng
        ];
    }
}
