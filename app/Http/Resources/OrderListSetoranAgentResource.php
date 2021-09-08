<?php

namespace App\Http\Resources;

use App\Repositories\OrderDetailRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListSetoranAgentResource extends JsonResource
{
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $chairs = OrderDetailRepository::findForPriceDistributionByUserAndDateAndFleet($this->user_id,$this->reserve_at, $this->fleet_route->fleet_detail->fleet_id);

        $checkpoint_max_index = count($this->fleet_route->route->checkpoints) - 1;
        $checkpoint_destination = $this->fleet_route->route->checkpoints()->where('agency_id', $this->destination_agency_id)->first();
        $agent_start = $this->agency;
        return [
            'id'=>$this->id,
            'fleet_name'=>$this->fleet_route?->fleet_detail?->fleet?->name,
            'fleet_id'=>$this->fleet_route?->fleet_detail?->fleet_id,
            'chairs_count'=>count($chairs),
            'deposit'=>abs($this->distribution()->sum('for_owner')),
            'checkpoints'=> new CheckpointStartEndResource($this->fleet_route->route, $checkpoint_destination, $agent_start)
        ];
    }
}
