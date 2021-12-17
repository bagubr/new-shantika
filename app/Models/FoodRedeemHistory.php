<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRedeemHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_detail_id', 'restaurant_id'
    ];
    protected $appends = ['price_food'];


    public function order_detail()
    {
        return $this->hasOne(OrderDetail::class, "id", "order_detail_id");
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function getPriceFoodAttribute()
    {
        return $this->order_detail?->order?->fleet_route?->fleet_detail?->fleet?->fleetclass?->price_food;
    }
}
