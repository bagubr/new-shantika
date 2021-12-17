<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleNotOperate extends Model
{
    use HasFactory;
    protected $fillable = [
        'fleet_route_id', 'note', 'schedule_at'
    ];

    public function fleet_route()
    {
        return $this->belongsTo(FleetRoute::class, 'fleet_route_id');
    }
    
}
