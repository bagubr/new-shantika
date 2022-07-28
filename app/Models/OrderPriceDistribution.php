<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPriceDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'for_food', 'for_travel', 'for_member', 'for_agent', 'for_owner', 'for_owner_with_food', 'for_owner_gross', 'ticket_only','deposited_at','total_deposit','ticket_price','charge'
    ];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function order_detail()
    {
        return $this->hasManyThrough(Order::class,  OrderDetail::class, 'order_id', 'id', 'order_id', 'id');
    }
}
