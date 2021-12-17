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
        if(empty($this->attributes('id'))) {
            return [];
        }
        return [
            'id'=>$this->id ?? "",
            'agency_name'=>$this->name ?? "",
            'city_name'=>$this->city?->name ?? "",
            'agency_address'=>$this->address ?? "",
            'agency_phone'=>$this->phone ?? "",
            'phone'=>array_merge((array) $this->phone, @$this->userAgent?->pluck('user.phone')?->toArray() ?? []),
            'agency_avatar'=>$this->avatar_url,
            'agency_lat'=> $this->lat,
            'agency_lng'=> $this->lng,
            'morning_time'=> $this->morning_time,
            'night_time'=> $this->night_time,
        ];
    }
}
