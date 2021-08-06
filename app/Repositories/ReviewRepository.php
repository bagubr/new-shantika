<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Review;

class ReviewRepository {
    public static function getHistoryOfAgent($user_id) {
        return Review::whereHas('order', function($query) use ($user_id) {
            $query->where('departure_agency_id', $user_id);
        })->get();
    }

    public static function getUserAgentReviewedByUserByOrder(Order|int $order, $user_id) {
        if(is_int($order)) {
            $order = Order::find($order);
        }
        return Review::whereHas('order', function($query) use ($order, $user_id) {
            $query->where('route_id', $order->route_id)
                ->where('departure_agency_id', $order->departure_agency_id)
                ->where('user_id', $user_id);
        })->first();
    }
}
    