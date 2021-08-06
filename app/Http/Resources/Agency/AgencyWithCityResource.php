<?php

namespace App\Http\Resources\Agency;

use Illuminate\Http\Resources\Json\JsonResource;

class AgencyWithCityResource extends JsonResource
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
            'id' => $this->id,
            'name' => ($request->city_id)?$this->name:$this->name.'-'.$this->city->name,
        ];
    }
}
