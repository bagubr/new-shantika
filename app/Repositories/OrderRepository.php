<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\Order\OrderListCustomerResource;

class OrderRepository {

    public static function getByUserId($user_id)
    {
        if($user_id){
            $order = Order::whereUserId($user_id)
            ->orderBy('created_at', 'desc')
            ->get();
            return OrderListCustomerResource::collection($order);
        }else{
            return [];
        }
    }
    
    public static function getByArrayId(array $order_id) {
        return Order::whereIn('id', $order_id)
        ->orderBy('created_at', 'desc')
        ->get();
    }
    
    public static function findWithDetailWithPayment($id)
    {
        return Order::with(['order_detail', 'payment'])->find($id);
    }

    public static function unionBookingByUserIdAndDate($user_id, $date) {
        $booking = Booking::select('id', 'route_id', 'user_id', 'created_at as reserve_at', 'status')
        ->addSelect(DB::raw("'BOOKING' as type"))
        ->where('expired_at', '>', date('Y-m-d H:i:s'))
        ->whereDate('created_at', date('Y-m-d H:i:s', strtotime($date)))
        ->whereUserId($user_id);
        $union =  Order::select('id', 'route_id', 'user_id', 'reserve_at', 'status')
            ->whereUserId($user_id)
            ->addSelect(DB::raw("'PEMBELIAN' as type"))
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
        