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

    public static function findForPriceDistributionByUserAndDateAndFleet($user_id, $date, $fleet_id) {
        $order = OrderDetail::with(['order', 'order.route.fleet', 'order.route.checkpoints', 'order.payment', 'order.distribution'])
            ->whereHas('order', function($query) use ($date) {
                $query->whereDate('created_at', $date);
            })
            ->whereHas('order',function($query) use ($user_id) {
                $query->where(function($subquery) use ($user_id) {
                    $subquery->where('departure_agency_id', $user_id)
                        ->whereHas('user.agencies')
                        ->whereIn('status', [Order::STATUS3]);
                })
                ->orWhere(function($subquery) use ($user_id) {
                    $subquery->where('departure_agency_id', $user_id)
                    ->whereDoesntHave('user.agencies')
                    ->whereIn('status', [Order::STATUS5, Order::STATUS8]);
                });
            })
            ->whereHas('order.route', function($query) use ($fleet_id) {
                $query->where('fleet_id', $fleet_id);
            })
            ->get();

        return $order;
    }

    public static function paginateAllByAgencyId(User $user, $date) {
        $user_order =  OrderDetail::whereHas('order', function($query) use ($user) {
            $query->where('departure_agency_id', $user->agencies->agency_id);
        })
        ->paginate(20);
        return $user_order;
    }

    public static function firstForPossibleCustomer($order_detail_id) {
        $order_detail = OrderDetail::with(['order.distribution'])->where('id', $order_detail_id)->first();

        return $order_detail;
    }
}
