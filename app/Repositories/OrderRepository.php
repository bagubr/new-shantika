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
    
    public static function getByArrayId($order_id) {
        if($order_id){
        $order = Order::whereIn('id', $order_id)
        ->orderBy('created_at', 'desc')
        ->get();
        return OrderListCustomerResource::collection($order);
        }else{
            return [];
        }
    }
    
    public static function findWithDetailWithPayment($id)
    {
        return Order::with(['order_detail', 'payment'])->find($id);
    }

    public static function unionBookingByUserIdAndDate($user_id, $date) {
        $booking = Booking::select('id', 'route_id', 'user_id', 'created_at as reserve_at', 'status', 'code_booking as code')
        ->addSelect(DB::raw("'BOOKING' as type"))
        ->where('expired_at', '>', date('Y-m-d H:i:s'))
        ->whereDate('created_at', date('Y-m-d H:i:s', strtotime($date)))
        ->whereUserId($user_id)
        ->distinct('code_booking');
        $union =  Order::select('id', 'route_id', 'user_id', 'reserve_at', 'status', 'code_order as code')
            ->whereUserId($user_id)
            ->addSelect(DB::raw("'PEMBELIAN' as type"))
            ->whereDate('created_at', date('Y-m-d H:i:s', strtotime($date)))
            ->union($booking)
            ->get();
        return $union;
    }

    public static function findByCodeOrder($code_order)
    {
        return Order::with('route.checkpoints')->where('code_order', $code_order)->first();
    }

    public static function countBoughtRouteByAgencyByDate($token, $date) {
        $user = UserRepository::findByToken($token);

        return Order::whereHas('user.agencies', function($subquery) use ($user, $date) {
                $subquery->where('id', $user->agencies->id);
            })
            ->where('status', Order::STATUS3)
            ->whereDate('created_at', $date)
            ->count();
    }

    public static function getBoughtRouteByAgencyByDate($token, $date) {
        $user = UserRepository::findByToken($token);

        return Order::whereHas('user.agencies', function($subquery) use ($user, $date) {
                $subquery->where('id', $user->agencies->id);
            })
            ->where('status', Order::STATUS3)
            ->whereDate('created_at', $date)
            ->get();
    }

    public static function findForPriceDistribution($id) {
        return Order::with(['order_detail', 'route.fleet', 'route.checkpoints', 'payment', 'distribution'])->where('id', $id)->first();
    }

    public static function isExistAtDateByLayoutChair($date, $layout_chair_id, $user_id = null) {
        return Order::where(function($query) use ($date) {
                $query->where(function($subquery) {
                    $subquery->whereIn('status', Order::STATUS_BOUGHT);
                })
                ->whereDate('reserve_at', $date);
            })
            ->when($user_id, function($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->whereHas('order_detail', function($query) use ($layout_chair_id) {
                $query->where('layout_chair_id', $layout_chair_id);
            })
            ->exists();
    }
}
        