<?php

namespace App\Http\Resources\Route;

use App\Http\Resources\CheckpointStartEndResource;
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
        return [
            'id'                 => $this->id,
            'layout_id'          => $this->fleet->layout->id,
            'route_name'         => $this->name,
            'fleet_name'         => $this->fleet?->name ?? "",
            'departure_at'       => $this->departure_at,
            'arrived_at'         => $this->arrived_at,
            'fleet_class'        => $this->fleet_class,
            'price'              => $this->price,
            'chairs_available'   => rand(1, 32),
            'checkpoints'        => new CheckpointStartEndResource($this)
        ];
    }
}
