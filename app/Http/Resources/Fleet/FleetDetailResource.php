<?php

namespace App\Http\Resources\Fleet;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Facility;
class FleetDetailResource extends JsonResource
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
            'description'   => $this->description,
            'image'         => $this->image,
            'images'        => $this->images,
            'fleet_class'   => $this->fleetclass?->name??'',
            'total_chair'   => $this->layout?->total_indexes??'',
            'estimate_time' => date('G:i', strtotime($this->route?->departure_at) - strtotime($this->route?->arrived_at)),
            'facilities'    => Facility::orderBy('id', 'desc')->get(),
            'route'         => $this->route?->checkpoints?->makeHidden(['order', 'updated_at', 'created_at'])??'',
        ];
    }
}
