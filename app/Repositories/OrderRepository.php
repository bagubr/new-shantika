<?php

namespace App\Repositories;
use App\Models\Order;
class OrderRepository {

    public static function getByUserId($user_id)
    {
        return Order::whereUserId($user_id)->get();
    }
    
    public static function findWithDetail($id)
    {
        return Order::with('order_detail')->find($id);
    }
}
        