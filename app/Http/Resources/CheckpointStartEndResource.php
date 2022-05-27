<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckpointStartEndResource extends JsonResource
{
    protected $destination_agent;
    protected $start_agent;

    public function __construct($resource, $destination_agent = null, $start_agent = null)
    {
        parent::__construct($resource);

        $this->destination_agent = $destination_agent;
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
        // dd($checkpoint_max_index);
        // dd($this->checkpoints[7]);
        return [
            'start'         => $this->when($this->start_agent, new AgencyResource($this->start_agent), (object) []),
            'destination'   => $this->when($this->destination_agent != null, new AgencyResource($this->destination_agent), (object) []),
            'end'           => new CheckpointResource(@$this->checkpoints[$checkpoint_max_index])??'',
        ];
    }
}
