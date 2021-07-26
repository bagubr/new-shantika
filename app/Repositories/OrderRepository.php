<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderRepository {

    public static function getByUserId($user_id)
    {
        return Order::whereUserId($user_id)->get();
    }

    public static function getByUserIdAndDate($user_id, $date) {
        return Order::whereUserId($user_id)->where('created_at', 'ilike', '%'.$date.'%')->get();
    }
    
    public static function findWithDetailWithPayment($id)
    {
        return Order::with(['order_detail', 'payment'])->find($id);
    }

    public static function unionBookingByUserIdAndDate($user_id, $date) {
        $booking = Booking::whereUserId($user_id)
            ->select('route_id', 'user_id');
        return Order::whereUserId($user_id)->where('created_at', 'ilike', '%'.$date.'%')
            ->select('route_id', 'user_id')
            ->union($booking)
            ->get();
    }
}
        