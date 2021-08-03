<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $table = 'routes';
    protected $fillable = [
        'name', 'fleet_id', 'area_id', 'departure_at', 'arrived_at', 'price'
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

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class, 'route_id', 'id');
    }
    public function order()
    {
        return $this->hasMany(Order::class, 'route_id', 'id');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'route_id', 'id');
    }
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Order::class);
    }
    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Order::class);
    }
    public function order_detail()
    {
        return $this->hasManyThrough(OrderDetail::class, Order::class);
    }
    public  function schedule_not_operates()
    {
        return $this->hasMany(ScheduleNotOperate::class, 'route_id', 'id');
    }

    public function getFleetClassAttribute()
    {
        return $this->fleet()->first()->fleetclass()->first()->name;
    }
}
