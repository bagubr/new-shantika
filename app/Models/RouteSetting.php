<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'agency_id', 'fleet_route_id', 'start_at', 'end_at'
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function fleet_route()
    {
        return $this->belongsTo(FleetRoute::class);
    }
}
