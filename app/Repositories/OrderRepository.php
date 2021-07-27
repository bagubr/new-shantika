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
        return Order::whereUserId($user_id)
        ->whereDate('created_at', date('Y-m-d H:i:s', strtotime($date)))
        ->orderBy('created_at', 'desc')
        ->get();
    }
    
    public static function findWithDetailWithPayment($id)
    {
        return Order::with(['order_detail', 'payment'])->find($id);
    }

    public static function findBookingById($id)
    {
        return Booking::with(['route', 'layout_chair'])->find($id);
    }

    public static function unionBookingByUserIdAndDate($user_id, $date) {
        $booking = Booking::select('id', 'route_id', 'user_id', 'created_at as reserve_at', 'status')
        ->addSelect(\DB::raw("'BOOKING' as type"))
        ->where('expired_at', '>', date('Y-m-d H:i:s'))
        ->whereUserId($user_id);
        $union =  Order::select('id', 'route_id', 'user_id', 'reserve_at', 'status')
            ->whereUserId($user_id)
            ->addSelect(\DB::raw("'PEMBELIAN' as type"))
            ->whereDate('created_at', date('Y-m-d H:i:s', strtotime($date)))
            ->union($booking)
            ->get();
        return $union;
    }

    public static function findByCodeOrder($code_order)
    {
        return Order::where('code_order', $code_order)->first();
    }
}
        