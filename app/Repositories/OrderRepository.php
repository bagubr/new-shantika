<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\Order\OrderListCustomerResource;
use App\Models\User;

class OrderRepository {

    public static function getByUserId($user_id)
    {
        if($user_id){
            $order = Order::whereUserId($user_id)
            ->orderBy('created_at', 'desc')
            ->get();
            return $order;
        }else{
            return [];
        }
    }
    
    public static function getByArrayId($order_id) {
        if($order_id){
        $order = Order::whereIn('id', $order_id)
        ->orderBy('created_at', 'desc')
        ->get();
        return $order;
        }else{
            return [];
        }
    }
    
    public static function findWithDetailWithPayment($id)
    {
        return Order::with(['order_detail', 'payment'])->find($id);
    }

    public static function unionBookingByUserIdAndDate(User $user, $date) {
        $booking = Booking::select('id', 'route_id', 'user_id', 'booking_at as reserve_at', 'status', 'code_booking as code', 'layout_chair_id')
            ->addSelect(DB::raw("'BOOKING' as type"))
            ->addSelect(DB::raw("(select price from routes where routes.id = bookings.route_id) as price"))
            ->addSelect(DB::raw("(select agency_id as destination_agency_id from checkpoints where checkpoints.route_id = bookings.route_id and checkpoints.agency_id = bookings.user_id limit 1) as destination_agency_id"))
            ->where('expired_at', '>', date('Y-m-d H:i:s'))
            ->whereDate('created_at', date('Y-m-d H:i:s', strtotime($date)))
            ->whereUserId($user->id)
            ->distinct('code_booking');
        $agen_order =  Order::select('id', 'route_id', 'user_id', 'reserve_at', 'status', 'code_order as code')
            ->addSelect(DB::raw("NULL as layout_chair_id"))
            ->addSelect(DB::raw("'PEMBELIAN' as type"))
            ->addSelect(DB::raw("price"))
            ->addSelect(DB::raw("(select agency_id as destination_agency_id from checkpoints where checkpoints.route_id = orders.route_id and checkpoints.agency_id = orders.destination_agency_id) as destination_agency_id"))
            ->whereUserId($user->id)
            ->whereDate('created_at', date('Y-m-d H:i:s', strtotime($date)))
            ->union($booking);
        $user_order =  Order::select('id', 'route_id', 'user_id', 'reserve_at', 'status', 'code_order as code')
            ->addSelect(DB::raw("NULL as layout_chair_id"))
            ->addSelect(DB::raw("'EXCHANGE' as type"))
            ->addSelect(DB::raw("price"))
            ->addSelect(DB::raw("(select agency_id as destination_agency_id from checkpoints where checkpoints.route_id = orders.route_id and checkpoints.agency_id = orders.destination_agency_id) as destination_agency_id"))
            ->where('departure_agency_id', $user->agencies->agent->id)
            ->where('status', Order::STATUS5)
            ->whereDate('created_at', date('Y-m-d H:i:s', strtotime($date)))
            ->union($agen_order)
            ->get();
        return $user_order;
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

        $order = Order::whereHas('user.agencies', function($subquery) use ($user, $date) {
                $subquery->where('id', $user->agencies->id);
            })
            ->with(['route.fleet'])
            ->where('status', Order::STATUS3)
            ->whereDate('created_at', $date)
            ->get()
            ->groupBy('route.fleet.id')
            ->all();
        
        $order = array_values($order);
        
        return @$order[0];
    }

    public static function getAtDate($date) {
        return Order::with('order_detail')->where(function($query) use ($date) {
                $query->whereIn('status', Order::STATUS_BOUGHT)
                    ->whereDate('reserve_at', $date);
            })
            ->get();
    }

    public static function findForPriceDistributionByDateAndFleet($user_id, $date, $fleet_id) {
        $order = Order::with(['order_detail.chair', 'route.fleet', 'route.checkpoints', 'payment', 'distribution'])
            ->whereDate('created_at', $date)
            ->whereHas('route', function($query) use ($fleet_id) {
                $query->where('fleet_id', $fleet_id);
            })
            ->where('user_id', $user_id)
            ->where('status', Order::STATUS3)
            ->get();

        return $order;
    }

    public static function isOrderUnavailable($route_id,$date, $layout_chair_id) {
        return Order::where('route_id', $route_id)
        ->where('reserve_at', 'ilike', '%'.$date.'%')
        ->whereHas('order_detail', function($query) use ($layout_chair_id) {
            $query->whereIn('layout_chair_id', is_array($layout_chair_id) ? $layout_chair_id : [$layout_chair_id]);
        })
        ->whereIn('status', Order::STATUS_BOUGHT)
        ->exists();
    }
}
        