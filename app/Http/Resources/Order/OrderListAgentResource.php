<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointResource;
use App\Http\Resources\CheckpointStartEndResource;
use App\Http\Resources\OrderDetailChairResource;
use App\Models\Booking;
use App\Repositories\AgencyDepartureTimeRepository;
use App\Repositories\CheckpointRepository;
use App\Utils\PriceTiket;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListAgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->load(['fleet_route.route', 'fleet_route.fleet_detail.fleet.fleetclass', 'agency']);
        $this->agency = $this->load(['agency'])->agency;
        if($this->agency == null) {
            $this->agency = $this->load('user.agencies.agent')->user?->agencies?->agent;
        }

        $fleet_route = $this->fleet_route_with_trash;
        $route = $fleet_route->first()->route;
        $checkpoints = $route?->checkpoints;
        $fleet_detail = $fleet_route?->fleet_detail;
        $fleet = $fleet_detail?->fleet;

        $agency_destiny = $this->agency_destiny;
        $agent_start = $this->agency;
        return [
            'id'                        => $this->id,
            'layout_chair_id'           => $this->getLayoutChairId(),
            'fleet_route_id'            => $fleet_route?->id,
            'code'                      => $this->code,
            'name_fleet'                => $fleet?->name ?? "",
            'fleet_class'               => $fleet?->fleetclass?->name ?? "",
            'chairs'                    => @OrderDetailChairResource::collection($this->order_detail),
            'price'                     => PriceTiket::priceTiket($fleet_route, $agent_start, $agency_destiny, $this->reserve_at),
            'time_classification_id'    => $this->time_classification_id,
            'reserve_at'                => $this->reserve_at,
            'status'                    => $this->status,
            'type'                      => $this->type,
            'checkpoints'               => new CheckpointStartEndResource($route, $agency_destiny, $agent_start),
        ];
    }

    private function getLayoutChairId() {
        $booking = Booking::where('code_booking', $this->code)->pluck('layout_chair_id');
        $order_detail = $this->order_detail?->pluck('layout_chair_id');
        return $booking->isNotEmpty() ? $booking : $order_detail; 
    }
}
