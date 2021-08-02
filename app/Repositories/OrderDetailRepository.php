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

    public static function findForPriceDistributionByDateAndFleet($date, $fleet_id) {
        $order = OrderDetail::with(['order', 'order.route.fleet', 'order.route.checkpoints', 'order.payment', 'order.distribution'])
            ->whereHas('order', function($query) use ($date) {
                $query->whereDate('created_at', $date)->where('status', Order::STATUS3);
            })
            ->whereHas('order.route', function($query) use ($fleet_id) {
                $query->where('fleet_id', $fleet_id);
            })
            ->get();

        return $order;
    }
}
