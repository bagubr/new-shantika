<?php

namespace App\Http\Resources\Agency;

use App\Models\User;
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
            'phone'=> $this->getAgentIsActivePhone($this->id),
            'agency_avatar'=>$this->avatar_url,
            'agency_lat'=> $this->lat,
            'agency_lng'=> $this->lng,
            'morning_time'=> $this->morning_time,
            'night_time'=> $this->night_time,
        ];
    }
    public function getAgentIsActivePhone($id)
    {
        $phone = User::whereHas('agencies', function ($q) use ($id){
            $q->where('agency_id', $id);
        })->where('is_active', true)->pluck('phone');
        return $phone;
    }
}
