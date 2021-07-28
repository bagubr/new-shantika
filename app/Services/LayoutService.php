<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Layout;
use App\Models\Order;
use App\Models\Route;

class LayoutService {
    public static function getAvailibilityChairs(Layout $layout, Route $route, $date = null) {
        if(empty($date)) {
            $date = date('Y-m-d');
        }
        $layout->chairs = $layout->chairs->map(function ($item) use ($route, $date) {
            $item->is_booking = Booking::where('route_id', $route->id)->where('layout_chair_id', $item->id)->where('expired_at', '>=', date('Y-m-d H:i:s'))->exists();
            $item->is_unavailable = Order::where(function($query) use ($date) {
                    $query->where(function($subquery) {
                        $subquery->where('status', Order::STATUS1)
                            ->orWhere('status', Order::STATUS3)
                            ->orWhere('status', Order::STATUS5);
                    })
                    ->whereDate('reserve_at', $date);
                })
                ->whereHas('order_detail', function($query) use ($item) {
                    $query->where('layout_chair_id', $item->id);
                })
                ->exists();
            return $item;
        });

        return $layout;
    }
}
        