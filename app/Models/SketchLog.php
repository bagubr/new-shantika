<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SketchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_date',
        'to_date',
        'order_id',
        'from_fleet_route_id',
        'to_fleet_route_id',
        'from_layout_chair_id',
        'to_layout_chair_id',
        'from_time_classification_id',
        'to_time_classification_id'
    ];
}
