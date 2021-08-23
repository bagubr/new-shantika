<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FleetRoute extends Model
{
    use HasFactory;

    protected $table = 'fleet_routes';

    protected $fillable = [
        'fleet_id', 'route_id', 'departure_at', 'arrived_at', 'price', 'is_active'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'fleet_id', 'id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }
}
