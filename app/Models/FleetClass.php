<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

class FleetClass extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['name', 'price_food'];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $appends = [
        'price_fleet_class1',
        'price_fleet_class2',
    ];

    public function getPriceFleetClass1Attribute()
    {
        return $this->prices()->where('area_id', 1)->whereDate('start_at', '<=',date(now()))->orderBy('id', 'desc')->first()?->price??0;
    }

    public function getPriceFleetClass2Attribute()
    {
        return $this->prices()->where('area_id', 2)->whereDate('start_at', '<=',date(now()))->orderBy('id', 'desc')->first()?->price??0;
    }

    public function price_fleet_class($area_id)
    {
        return $this->prices()->where('area_id', $area_id)->whereDate('start_at', '<=',date(now()))->orderBy('id', 'desc')->first()?->price??0;
    }
    
    public function price_food_fleet_class($area_id)
    {
        return $this->prices()->where('area_id', $area_id)->whereDate('start_at', '<=',date(now()))->orderBy('id', 'desc')->first()?->price??0;
    }

    public function fleets() {
        return $this->hasMany(Fleet::class, 'fleet_class_id', 'id');
    }

    public function prices() {
        return $this->hasMany(FleetClassPrice::class);
    }
}
