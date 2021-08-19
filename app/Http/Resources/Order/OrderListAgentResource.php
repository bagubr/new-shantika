<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointResource;
use App\Http\Resources\CheckpointStartEndResource;
use App\Http\Resources\OrderDetailChairResource;
use App\Models\Booking;
use App\Repositories\CheckpointRepository;
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
        $this->load('fleet_route.route.fleet.fleetclass');

        $fleet_route = $this->fleet_route;
        $route = $fleet_route->route;
        $checkpoints = $route->checkpoints;
        $fleet = $fleet_route->fleet;

        $checkpoint_max_index = count($checkpoints) - 1;
        $checkpoint_destination = CheckpointRepository::findByRouteAndAgency($route->id, $this->destination_agency_id);
        return [
            'id'                        => $this->id,
            'layout_chair_id'           => Booking::where('code_booking', $this->code)->pluck('layout_chair_id') ?? $this->order_detail?->pluck('layout_chair_id'),
            'fleet_route_id'            => $fleet_route->id,
            'code'                      => $this->code,
            'name_fleet'                => $fleet->name,
            'fleet_class'               => $fleet->fleetclass?->name,
            'departure_at'              => $route->departure_at,
            'chairs'                    => @OrderDetailChairResource::collection($this->order_detail),
            'price'                     => $this->price,
            'reserve_at'                => $this->reserve_at,
            'status'                    => $this->status,
            'type'                      => $this->type,
            'checkpoints'               => new CheckpointStartEndResource($route),
            'checkpoint_destination'    => new CheckpointResource($checkpoint_destination)
        ];
    }
}
