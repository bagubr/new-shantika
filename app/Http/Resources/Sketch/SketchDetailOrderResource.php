<?php

namespace App\Http\Resources\Sketch;

use Illuminate\Http\Resources\Json\JsonResource;

class SketchDetailOrderResource extends JsonResource
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
            'chairs'        => $this->chairs
        ];
        return $data;
    }
}
