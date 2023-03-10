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
        $chairs = OrderDetailRepository::findForPriceDistributionByUserAndDateAndFleet($this->user_id,$request->date, $this->fleet_route?->fleet_detail?->fleet_id);

        $agent_destination = $this->agency_destiny;
        $agent_start = $this->agency;
        return [
            'id'=>$this->id,
            'fleet_name'=>$this->fleet_route?->fleet_detail?->fleet?->name,
            'fleet_id'=>$this->fleet_route?->fleet_detail?->fleet_id,
            'chairs_count'=>count($chairs),
            'deposit'=>$this->total_deposit_fleet_route,
            'checkpoints'=> new CheckpointStartEndResource($this->fleet_route?->route, $agent_destination, $agent_start)
        ];
    }
}
