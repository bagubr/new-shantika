<?php

namespace App\Http\Resources\Layout;

use Illuminate\Http\Resources\Json\JsonResource;

class LayoutResource extends JsonResource
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
            'index'         => $this->index,
            'is_available'  => $this->is_available,
            'is_booking'    => (Booking::where('route_id', $request->route_id)->where('expired_at', date('Y-m-d H:i:s'))->first())?true:false,
            'is_door'       => $this->is_door,
            'is_toilet'     => $this->is_toilet,
        ];
    }
}
