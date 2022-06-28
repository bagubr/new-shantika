<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointResource;
use App\Http\Resources\CheckpointStartEndResource;
use App\Http\Resources\OrderDetailChairResource;
use App\Http\Resources\OrderDetailSetoranAgentResource;
use App\Repositories\CheckpointRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailAgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        try {
            $this->agency = @$this->load(['fleet_route.route', 'fleet_route.fleet_detail.fleet.fleetclass', 'agency'])->agency;
        } catch(\Exception $e) {
            $this->agency = $this->load('user.agencies.agent')->user->agencies->agent;
        }

<<<<<<< HEAD
        $fleet_route = $this->fleet_route_with_trash;
=======
        $fleet_route = $this->fleet_route;
>>>>>>> rilisv1
        $distribution = $this->distribution;
        $route = $fleet_route->route;
        $checkpoints = $route->checkpoints;
        $fleet_detail = $fleet_route->fleet_detail;
        $fleet = $fleet_detail->fleet;
        $order_detail = $this->order_detail;

        $agent_destination = $this->agency_destiny;
        $agent_start = $this->agency;
        $price_feed = $distribution?->for_food;
        $price_travel = $distribution?->for_travel;
        $price_member = $distribution?->for_member;

        return [
            'id'                        =>$this->id,
            'code_order'                =>$this->code_order ?? $this->code_booking,
            'name_fleet'                =>$fleet->name,
            'fleet_class'               =>$fleet->fleetclass?->name,
            'total_passenger'           =>count($order_detail ?? []),
            'checkpoints'               => new CheckpointStartEndResource($route, $agent_destination, $agent_start),
            'created_at'                =>date('Y-m-d H:i:s', strtotime($this->created_at)),
            'reserve_at'                =>date('Y-m-d H:i:s', strtotime($this->reserve_at)),
            'departure_at'              =>$this->agency->agency_departure_times->where('time_classification_id', $this->time_classification_id)->first()?->departure_at,
            'time_classification_id'    =>$this->time_classification_id,
            'status'                    =>$this->status,
            'name_passenger'            =>$order_detail[0]->name ?? "",
            'phone_passenger'           =>$order_detail[0]->phone ?? "",
            'seat_passenger'            =>$order_detail?->pluck('chair.name'),
            'chairs'                    =>$this->getChairs($order_detail),
            'price_member'              =>abs($price_member),
            'price_travel'              =>$price_travel,
            'price_feed'                =>$price_feed,
            'id_member'                 =>$this->id_member,
<<<<<<< HEAD
            'price'                     =>$distribution?->ticket_only,
            'total_price'               =>$this->price,
            'commision'                 =>$distribution?->for_agent,
            'review'                    =>$this->review
=======
            'price'                     =>$distribution?->ticket_only + $distribution?->for_member - $distribution?->for_travel,
            'total_price'               =>$this->price,
            'commision'                 =>$distribution?->for_agent,
            'review'                    =>$this->review,
            'note'                    =>$this->note
>>>>>>> rilisv1
        ];
    }

    private function getChairs($order_detail) {
        if ($order_detail != null) {
            return OrderDetailChairResource::collection($order_detail);
        }
        return [];
    }
}
