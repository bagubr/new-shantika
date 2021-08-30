<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FleetDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'fleet_id', 'plate_number', 'nickname'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'fleet_id');
    }
    public function fleet_route()
    {
        return $this->hasMany(FleetRoute::class, 'fleet_detail_id', 'id');
    }
}
