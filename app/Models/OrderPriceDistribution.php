<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPriceDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'for_food', 'for_travel', 'for_member', 'for_agent', 'for_owner'
    ];

    public function order() {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
