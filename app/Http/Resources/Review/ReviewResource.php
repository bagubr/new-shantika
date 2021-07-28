<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'id'=> $this->id,
            'avatar_fleet'=> $this->order?->route?->checkpoints[0]?->agency?->avatar,
            'name_fleet'=> $this->order?->route?->fleet?->name,
            'class_fleet'=> $this->order?->route?->fleet_class,
            'rating'=> $this->rating,
            'review'=> $this->review,
            'created_at'=> $this->created_at,
        ];
    }
}
