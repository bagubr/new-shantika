<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetClassPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id', 'fleet_class_id', 'start_at', 'price'
    ];

    public function area() {
        return $this->belongsTo(Area::class);
    }

    public function fleet_class() {
        return $this->belongsTo(FleetClass::class);
    }
}
