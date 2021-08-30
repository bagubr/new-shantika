<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckpointStartEndResource extends JsonResource
{
    protected $destination_checkpoint;
    protected $start_agent;

    public function __construct($resource, $destination_checkpoint = null, $start_agent = null)
    {
        parent::__construct($resource);

        $this->destination_checkpoint = $destination_checkpoint;
        $this->start_agent = $start_agent;
    }

    public static function collection($resource) {
        return parent::collection($resource);
    }

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
            'start'         => $this->when($this->start_agent, new AgencyResource($this->start_agent), (object) []),
            'destination'   => $this->when($this->destination_checkpoint != null, new CheckpointResource($this->destination_checkpoint), (object) []),
            'end'           => new CheckpointResource($this->checkpoints[$checkpoint_max_index]),
        ];
    }
}
