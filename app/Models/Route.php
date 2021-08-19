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
        'name', 'area_id', 'departure_city_id', 'destination_city_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function fleet()
    {
        return $this->hasManyThrough(Fleet::class, FleetRoute::class, 'fleet_id', 'id');
    }

<<<<<<< HEAD
    public function city_form()
    {
        return $this->belongsTo(City::class, 'departure_city_id', 'id');
    }
    public function city_to()
    {
        return $this->belongsTo(City::class, 'destination_city_id', 'id');
    }

=======
    public function fleet_routes() {
        return $this->hasMany(FleetRoute::class);
    }
    
>>>>>>> 717a67d917d84f245fc118b9ac982934c8ace2c8
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
}
