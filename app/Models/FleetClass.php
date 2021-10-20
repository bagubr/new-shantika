<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FleetClass extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['name', 'price_food'];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function fleets() {
        return $this->hasMany(Fleet::class, 'fleet_class_id', 'id');
    }

    public function prices() {
        return $this->hasMany(FleetClassPrice::class);
    }
}
