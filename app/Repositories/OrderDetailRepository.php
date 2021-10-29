<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;

class OrderDetailRepository
{
    public static function findById($order)
    {
        return OrderDetail::where('order_id', $order)->get();
    }

    public static function findForPriceDistributionByUserAndDateAndFleet($user_id, $date, $fleet_id, $time_classification_id = null) {
        $agency_id = User::with('agencies.agent')->find($user_id)?->agencies?->agent?->id;
        $order = OrderDetail::with(['order', 'order.fleet_route.fleet_detail.fleet', 'order.fleet_route.route.checkpoints', 'order.payment', 'order.distribution'])
            ->whereHas('order', function($query) use ($date, $time_classification_id) {
                $query->whereDate('reserve_at', $date)->when($time_classification_id, function($subquery) use ($time_classification_id) {
                    $subquery->where('time_classification_id', $time_classification_id);
                });
            })
            ->whereHas('order',function($query) use ($agency_id) {
                $query->whereIn('status', Order::STATUS_BOUGHT);
                if(!empty($agency_id)) {
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
                }
            })
            ->whereHas('order.fleet_route.fleet_detail', function($query) use ($fleet_id) {
                $query->where('fleet_id', $fleet_id);
            })
            ->get();

        return $order;
    }

    public static function getAllByAgencyId(User $user, $date) {
        $user_order =  OrderDetail::with(['order', 'chair'])->whereHas('order', function($query) use ($user, $date) {
            $agency_id = $user->agencies->agency_id;
            $query->where(function($q) use ($agency_id) {
                $q->where(function($subsubquery) use ($agency_id) {
                    $subsubquery->where('departure_agency_id', $agency_id)
                        ->whereHas('user.agencies')
                        ->whereIn('status', [Order::STATUS3]);
                })
                ->orWhere(function($subsubquery) use ($agency_id) {
                    $subsubquery->where('departure_agency_id', $agency_id)
                    ->whereDoesntHave('user.agencies')
                    ->whereIn('status', [Order::STATUS5, Order::STATUS8]);
                });
            })
            ->whereDate('reserve_at', $date);
        })
        ->join('orders', 'orders.id', '=', 'order_details.order_id')
        ->select('orders_details.*')
        ->orderBy('orders.fleet_route_id')
        ->orderBy('layout_chair_id', 'asc')
        ->get();
        return $user_order;
    }

    public static function firstForPossibleCustomer($order_detail_id) {
        $order_detail = OrderDetail::with(['order.distribution'])->where('id', $order_detail_id)->first();

        return $order_detail;
    }
}
