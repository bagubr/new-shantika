<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;

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
            ->where(function($query) use ($user_id) {
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
}
