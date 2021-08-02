<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\CheckpointStartEndResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderTiketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $checkpoint_max_index = count($this->route->checkpoints) - 1;
        return [
            'id' => $this->id,
            'code_order' => $this->code_order,
            'name_fleet' => $this->route->fleet->name,
            'name_passenger'=>$this->order_detail[0]->name,
            'seat_passenger'=>$this->order_detail->pluck('chair.name'),
            'created_at'=>date('Y-m-d H:i:s', strtotime($this->created_at)),
            'reserve_at'=>date('Y-m-d H:i:s', strtotime($this->reserve_at)),
            'checkpoints'        => new CheckpointStartEndResource($this->route),
        ];
    }
}
