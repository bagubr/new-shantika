<?php

namespace App\Http\Resources\Fleet;

use Illuminate\Http\Resources\Json\JsonResource;

class InformationFleetResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'fleet_class'   => $this->fleetclass->name,
            'route'         => $this->route->name??'',
            'total_chair'   => $this->layout->total_indexes,
        ];
    }
}
