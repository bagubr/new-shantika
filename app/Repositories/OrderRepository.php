<?php

namespace App\Repositories;
use App\Models\Order;
class OrderRepository {

    public static function getByUserId($user_id)
    {
        return Order::whereUserId($user_id)->get();
    }
    
    public static function findWithDetailWithPayment($id)
    {
        return Order::with(['order_detail', 'payment'])->find($id);
    }

    public static function findByCodeOrder($code_order) {
        return Order::where('code_order', $code_order)->first();
    }
}
        