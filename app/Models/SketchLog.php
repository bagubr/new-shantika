<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SketchLog extends Model
{
    use HasFactory;

    const TYPE1 = 'CHANGE';
    const TYPE2 = 'CANCELED'; 

    protected $fillable = [
        'admin_id',
        'from_date',
        'to_date',
        'order_id',
        'departure_agency_id',
        'from_fleet_route_id',
        'to_fleet_route_id',
        'from_layout_chair_id',
        'to_layout_chair_id',
        'from_time_classification_id',
        'to_time_classification_id',
        'type'
    ];

    public function from_fleet_route() {
        return $this->belongsTo(FleetRoute::class, "from_fleet_route_id");
    }

    public function to_fleet_route() {
        return $this->belongsTo(FleetRoute::class, "to_fleet_route_id");
    }

    public function from_layout_chair() {
        return $this->belongsTo(LayoutChair::class, "from_layout_chair_id");
    }

    public function to_layout_chair() {
        return $this->belongsTo(LayoutChair::class, "to_layout_chair_id");
    }

    public function from_time_classification() {
        return $this->belongsTo(TimeClassification::class, "from_time_classification_id");
    }

    public function to_time_classification() {
        return $this->belongsTo(TimeClassification::class, "to_time_classification_id");
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }

}
