<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckpointStartEndResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $checkpoint_max_index = count($this->checkpoints) - 1;
        return [
            'start'         => new CheckpointResource($this->checkpoints[0]),
            'destination'   => $this->when($this->destination_checkpoint != null, $this->destination_checkpoint),
            'end'           => new CheckpointResource($this->checkpoints[$checkpoint_max_index]),
        ];
    }
}
