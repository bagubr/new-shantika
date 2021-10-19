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

    public function order_detail() {
        return $this->hasOne(OrderDetail::class);
    }

    public function restaurant() {
        return $this->belongsTo(Restaurant::class);
    }
}