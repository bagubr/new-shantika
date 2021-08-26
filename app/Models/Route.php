<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'routes';

    protected $fillable = [
        'name'
    ];
    
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function fleet_detail()
    {
        return $this->hasManyThrough(Fleet::class, FleetRoute::class, 'fleet_id', 'id');
    }

    public function fleet_routes()
    {
        return $this->hasMany(FleetRoute::class);
    }

    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class, 'route_id', 'id');
    }

    public function order_detail()
    {
        return $this->hasManyThrough(OrderDetail::class, Order::class);
    }
}
