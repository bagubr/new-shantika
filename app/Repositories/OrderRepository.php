<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\Order\OrderListCustomerResource;
use App\Models\OrderDetail;
use App\Models\User;

class OrderRepository
{
    public static function getByUserId($user_id)
    {
        if ($user_id) {
            $order = Order::whereUserId($user_id)
                ->orderBy('created_at', 'desc')
                ->get();
            return $order;
        } else {
            return [];
        }
    }

    public static function getByArrayId($order_id)
    {
        if ($order_id) {
            $order = Order::whereIn('id', $order_id)
                ->orderBy('created_at', 'desc')
                ->get();
            return $order;
        } else {
            return [];
        }
    }

    public static function findWithDetailWithPayment($id)
    {
        return Order::with(['order_detail', 'payment'])->find($id);
    }

    public static function unionBookingByUserIdAndDate(User $user, $date)
    {
        $booking = Booking::select('id', 'fleet_route_id', 'user_id', 'booking_at as reserve_at', 'status', 'code_booking as code', 'layout_chair_id', 'destination_agency_id', 'time_classification_id')
            ->addSelect(DB::raw("0 as departure_agency_id"))
            ->addSelect(DB::raw("'BOOKING' as type"))
            ->addSelect(DB::raw("(select ap.price from agency_prices ap where ap.agency_id = bookings.destination_agency_id order by ap.start_at desc) as price"))
            ->where('expired_at', '>', date('Y-m-d H:i:s'))
            ->whereDate('booking_at', date('Y-m-d H:i:s', strtotime($date)))
            ->whereUserId($user->id)
            ->distinct('code_booking');
        $agen_order =  Order::select('id', 'fleet_route_id', 'user_id', 'reserve_at', 'status', 'code_order as code')
            ->addSelect(DB::raw("NULL as layout_chair_id"))
            ->addSelect('destination_agency_id', 'time_classification_id', 'departure_agency_id')
            ->addSelect(DB::raw("'PEMBELIAN' as type"))
            ->addSelect(DB::raw("(select o.ticket_price from order_price_distributions o where o.order_id = orders.id) as price"))
            ->whereHas('user.agencies')
            ->where('departure_agency_id', $user->agencies->agent->id)
            ->whereDate('reserve_at', date('Y-m-d H:i:s', strtotime($date)))
            ->union($booking);
        $user_order =  Order::select('id', 'fleet_route_id', 'user_id', 'reserve_at', 'status', 'code_order as code')
            ->addSelect(DB::raw("NULL as layout_chair_id"))
            ->addSelect('destination_agency_id', 'time_classification_id', 'departure_agency_id')
            ->addSelect(DB::raw("'EXCHANGE' as type"))
            ->addSelect(DB::raw("(select o.ticket_price from order_price_distributions o where o.order_id = orders.id) as price"))
            ->where('departure_agency_id', $user->agencies->agent->id)
            ->whereDoesntHave('user.agencies')
            ->whereIn('status', [Order::STATUS5, Order::STATUS8])
            ->whereDate('reserve_at', date('Y-m-d H:i:s', strtotime($date)))
            ->union($agen_order)
            ->get();
        return $user_order;
    }

    public static function findByCodeOrder($code_order)
    {
        return Order::with('fleet_route.route.checkpoints')->where('code_order', $code_order)->first();
    }

    public static function countBoughtRouteByAgencyByDate($token, $date, $is_distinct = false)
    {
        $agency_id = UserRepository::findByToken($token)?->agencies?->agent?->id;

        return Order::where(function($query) use ($agency_id) {
                $query->where(function($subquery) use ($agency_id) {
                    $subquery->where('departure_agency_id', $agency_id)
                        ->whereHas('user.agencies')
                        ->whereIn('status', [Order::STATUS3]);
                })
                ->orWhere(function($subquery) use ($agency_id) {
                    $subquery->where('departure_agency_id', $agency_id)
                    ->whereDoesntHave('user.agencies')
                    ->whereIn('status', [Order::STATUS5, Order::STATUS8]);
                });
            })
            ->when($is_distinct, function($query) {
                $query->distinct('fleet_route_id');
            })
            ->whereDate('reserve_at', $date)
            ->count();
    }

    public static function getBoughtRouteByAgencyByDate($token, $date)
    {
        $user = UserRepository::findByToken($token);
        $agency_id = $user->agencies?->agency_id;
        $date = date('Y-m-d', strtotime($date));

        $order = Order::where(function($query) use ($user) {
            $query->where(function($subquery) use ($user) {
                    $subquery->where('departure_agency_id', $user->agencies?->agent?->id)
                        ->whereHas('user.agencies')
                        ->whereIn('status', [Order::STATUS3]);
                })
                ->orWhere(function($subquery) use ($user) {
                    $subquery->where('departure_agency_id', $user->agencies?->agent?->id)
                    ->whereDoesntHave('user.agencies')
                    ->whereIn('status', [Order::STATUS5, Order::STATUS8]);
                });
            })
            ->with(['fleet_route.fleet_detail.fleet'])
            ->withSum('distribution', 'total_deposit')
            ->distinct('fleet_route_id')
            ->whereDate('reserve_at', $date)
            ->orderBy('fleet_route_id', 'asc')
            ->select()
            // ->addSelect(DB::raw("(select sum(total_deposit) from order_price_distributions opd left join orders o on o.id = opd.order_id where o.fleet_route_id = orders.fleet_route_id and o.reserve_at::text ilike '%$date%' and o.departure_agency_id = $agency_id) as total_deposit_fleet_route"))
            ->get();
            // ->groupBy('fleet_route.fleet_detail.fleet_id')
            // ->all();

        // $order = array_values($order);

        return @$order;
    }

    public static function getAtDateByFleetRouteId($date, $fleet_route_id)
    {
        return Order::with(['order_detail', 'user.agencies.agent', 'agency', 'agency_destiny'])->where(function ($query) use ($date) {
            $query->whereIn('status', Order::STATUS_BOUGHT)
                ->whereDate('reserve_at', $date);
            })
            ->where('fleet_route_id', $fleet_route_id)
            ->get();
    }

    public static function findForPriceDistributionByDateAndFleet($user_id, $date, $fleet_id, $time_classification_id = null)
    {
        $agency_id = User::with('agencies.agent')->find($user_id)->agencies?->agent?->id;
        $order = Order::with(['order_detail.chair', 'fleet_route.fleet_detail.fleet', 'fleet_route.route.checkpoints', 'payment', 'distribution'])
            ->whereDate('reserve_at', $date)
            ->whereHas('fleet_route.fleet_detail', function ($query) use ($fleet_id) {
                $query->where('fleet_id', $fleet_id);
            })
            ->when($time_classification_id, function($query) use ($time_classification_id) {
                $query->where('time_classification_id', $time_classification_id);
            })
            ->where(function($query) use ($agency_id) {
                $query->where(function($subquery) use ($agency_id) {
                    $subquery->where('departure_agency_id', $agency_id)
                        ->whereHas('user.agencies')
                        ->whereIn('status', [Order::STATUS3]);
                })
                ->orWhere(function($subquery) use ($agency_id) {
                    $subquery->where('departure_agency_id', $agency_id)
                    ->whereDoesntHave('user.agencies')
                    ->whereIn('status', [Order::STATUS5, Order::STATUS8]);
                });
            })
            ->get();
        return $order;
    }

    public static function isOrderUnavailable($fleet_route_id, $date, $layout_chair_id, $time_classification_id = null)
    {
        return Order::where('fleet_route_id', $fleet_route_id)
            ->where('reserve_at', 'ilike', '%' . $date . '%')
            ->whereHas('order_detail', function ($query) use ($layout_chair_id) {
                $query->whereIn('layout_chair_id', is_array($layout_chair_id) ? $layout_chair_id : [$layout_chair_id]);
            })
            ->when($time_classification_id, function($query) use ($time_classification_id) {
                $query->where('time_classification_id', $time_classification_id);
            })
            ->whereIn('status', Order::STATUS_BOUGHT)
            ->exists();
    }

    public static function getAtDateAndRoute($date, $route)
    {
        if(!$route || $route == 'WITH_TYPE'){
            return [];
        }
        return Order::where(function ($query) use ($date, $route) {
            $query->where('status', Order::STATUS3);
            $query->whereDate('reserve_at', $date);
            $query->whereRouteId($route);
        })->get();
    }

    public static function getAtDateAndFleet($date, $fleet)
    {
        if(!$fleet || $fleet == 'WITH_TYPE'){
            return [];
        }
        return Order::where(function ($query) use ($date, $fleet) {
            $query->where('status', Order::STATUS3);
            $query->whereDate('reserve_at', $date);
            $query->whereHas('route', function ($q) use ($fleet)
            {
                $q->whereFleetId($fleet);
            });
        })->get();
    }
    
    public static function getAtDateAndFleetRoute($date, $fleet_route)
    {
        if(!$fleet_route || $fleet_route == 'WITH_TYPE'){
            return [];
        }
        return Order::where(function ($query) use ($date, $fleet_route) {
            $query->where('status', Order::STATUS3);
            $query->whereDate('reserve_at', $date);
            $query->whereFleetRouteId($fleet_route);
        })->get();
    }

    public static function getAtDateAndFleetDetail($date, $fleet_detail)
    {
        if(!$fleet_detail || $fleet_detail == 'WITH_TYPE'){
            return [];
        }
        return Order::where(function ($query) use ($date, $fleet_detail) {
            $query->where('status', Order::STATUS3);
            $query->whereDate('reserve_at', $date);
            $query->whereHas('fleet_route', function ($q) use ($fleet_detail)
            {
                $q->whereFleetDetailId($fleet_detail);
            });
        })->get();
    }
}
