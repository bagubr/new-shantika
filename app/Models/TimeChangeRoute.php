<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeChangeRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_classification_id', 'fleet_route_id', 'date', 'fleet_detail_id'
    ];

    protected $appends = [
        'fleet_route'
    ];

    public function getFleetRouteAttribute()
    {
        return $this->fleet_route()?->first()?->fleet_detail()?->first()?->fleet()?->first()?->name.'/'.$this->fleet_route()?->first()?->fleet_detail()?->first()?->fleet()?->first()?->fleetclass()?->first()?->name.'('.$this->fleet_route()?->first()?->fleet_detail()?->first()?->nickname.')('.$this->fleet_route()?->first()?->route()?->first()?->name.')';
    }

    public function fleet_route()
    {
        return $this->belongsTo(FleetRoute::class);
    }

    public function fleet_detail()
    {
        return $this->belongsTo(FleetDetail::class);
    }

    public function time_classification()
    {
        return $this->belongsTo(TimeClassification::class);
    }
}
