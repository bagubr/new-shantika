<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetRoutePrice extends Model
{
    use HasFactory;

    protected $table = 'fleet_route_prices';

    protected $fillable = [
        'fleet_route_id',
        'start_at',
        'end_at',
        'price'
    ];

    public function fleet_route() {
        return $this->belongsTo(FleetRoute::class);
    }
}
