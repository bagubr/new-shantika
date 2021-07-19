<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $table = 'routes';
    protected $fillable = [
        'name', 'fleet_id', 'departure_at', 'arrived_at', 'price'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $appends = [
        'fleet_class'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'fleet_id', 'id');
    }

    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class, 'route_id', 'id');
    }

    public function getFleetClassAttribute()
    {
        return $this->fleet()->first()->fleetclass()->first()->name;
    }
}
