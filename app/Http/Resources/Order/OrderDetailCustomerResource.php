<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointResource;
use App\Http\Resources\CheckpointStartEndResource;
use App\Models\Order;
use App\Models\Setting;
use App\Models\TimeClassification;
use App\Repositories\CheckpointRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $fleet_route = $this->fleet_route;
        $route = $fleet_route->route;

        $agency_destiny = $this->agency_destiny;
        $agent_start = $this->agency;
        return [
            'id' => $this->id,
            'code_order' => $this->code_order,
            'name_fleet' => $fleet_route->fleet_detail?->fleet?->name,
            'fleet_class' => $fleet_route->fleet_detail?->fleet?->fleetclass?->name,
            'total_passenger' => count($this->order_detail),
            'checkpoints'        => new CheckpointStartEndResource($route, $agency_destiny, $agent_start),
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'reserve_at' => date('Y-m-d H:i:s', strtotime($this->reserve_at)),
            'departure_at'  => $this->agency->agency_departure_times->where('time_classification_id', $this->time_classification_id)->first()->departure_at,
            'status' => $this->status,
            'name_passenger' => $this->order_detail[0]->name,
            'phone_passenger' => $this->order_detail[0]->phone,
            'seat_passenger' => $this->order_detail->pluck('chair.name'),
            'price_travel' => $this->getPriceTravel($this),
            'price_member' => $this->getPriceMember($this),
            'price_feed' => $this->getPriceFeed($this),
            'id_member' => $this->id_member,
            'payment_type' => $this->payment?->payment_type?->name,
            'payment_charge'=>$this->distribution->charge,
            'price' => $this->price,
            'review' => $this->review ?? (object) [],
        ];
    }

    public function getPriceFeed($order)
    {
        return abs($order->distribution->for_food / count($order->order_detail));
    }

    public function getPriceTravel($order)
    {
        return abs($order->distribution->for_travel);
    }

    public function getPriceMember($order)
    {
        return abs($order->distribution->for_member);
    }
}
