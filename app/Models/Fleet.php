<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Fleet extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'fleets';
    protected $fillable = ['name', 'description', 'fleet_class_id', 'image', 'layout_id', 'images'];

    public function layout()
    {
        return $this->hasOne(Layout::class, 'id', 'layout_id');
    }

    public function fleet_routes()
    {
        return $this->hasMany(FleetRoute::class, 'fleet_id', 'id');
    }

    public function fleetclass()
    {
        return $this->belongsTo(FleetClass::class, 'fleet_class_id', 'id');
    }

    public function fleet_detail()
    {
        return $this->hasMany(FleetDetail::class, 'fleet_id', 'id');
    }

    public function getImageAttribute($value)
    {
        return url('storage/' . $value);
    }

    public function getImagesAttribute($value)
    {
        $values = json_decode($value);
        return array_map(function ($item) {
            return url('storage/' . $item);
        }, $values);
    }

    public function deleteImage()
    {
        Storage::disk('public')->delete($this->attributes['image']);
    }

    public function agency_fleet()
    {
        return $this->hasMany(AgencyFleet::class, 'fleet_id', 'id');
    }
}
