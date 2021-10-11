<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetRoutePrice extends Model
{
    use HasFactory;

    // This model / table are used for increasing or decreasing the price & applying fleet routes to the certain price
    // that the price are seted in table cities / model City.

    protected $fillable = [
        'fleet_route_id',
        'start_at',
        'end_at',
        'deviation_price',
        'note',
        'color'
    ];

    public function fleet_route()
    {
        return $this->belongsTo(FleetRoute::class);
    }
}
