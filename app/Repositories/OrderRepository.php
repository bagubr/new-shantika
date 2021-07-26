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
        $booking = Booking::with('layout_chair')->select('id', 'route_id', 'user_id', 'created_at as reserve_at', 'status')
        ->whereUserId($user_id);
        $union =  Order::select('id', 'route_id', 'user_id', 'reserve_at', 'status')
            ->whereUserId($user_id)
            ->whereDate('created_at', date('Y-m-d H:i:s', strtotime($date)))
            ->union($booking)
            ->get();
        return $union;  
    }
}
        