<?php

namespace App\Scopes\FleetRoute;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class PriceScope implements Scope
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function apply(Builder $builder, Model $fleet_route)
    {
        $date = $this->date ?? '';
        return $builder->addSelect(DB::raw("(select price from fleet_route_prices where fleet_route_id = fleet_routes.id and start_at <= '$date' and end_at >= '$date' order by created_at desc limit 1) as price"));
    }    
}