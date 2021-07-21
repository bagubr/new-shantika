<?php

namespace App\Http\Resources\Layout;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Booking;
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
        $data = [
            'id'            => $this->id,
            'name'          => $this->name,
            'row'           => $this->row,
            'col'           => $this->col,
            'space_indexes' => $this->space_indexes,
            'toilet_indexes'=> $this->toilet_indexes,
            'door_indexes'  => $this->door_indexes,
            'note'          => $this->note,
            'total_indexes' => $this->total_indexes,
            'chairs'        => $this->chairs->map(function ($item, $key) use ($request)
            {
                $booking = Booking::where('route_id', $request->id)->where('layout_chair_id', $item->id)->where('expired_at', '>=', date('Y-m-d H:i:s'))->first();
                $item->is_booking = ($booking)?true:false;
                return $item;
            }),
        ];
        return $data;
    }
}
