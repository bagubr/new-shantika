<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\FleetRoute;
use App\Models\Layout;
use App\Models\Order;
use App\Models\Route;
use App\Repositories\BookingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;

class LayoutService {
    public static function getAvailibilityChairs(Layout $layout, FleetRoute $fleet_route, $date = null) {
        if(empty($date)) {
            $date = date('Y-m-d');
        }
        $user_id = UserRepository::findByToken(request()->bearerToken())?->id;
        $booking = BookingRepository::getTodayByRoute($fleet_route->id);
        $unavailable = OrderRepository::getAtDate($date);

        $layout->chairs = $layout->chairs->map(function ($item) use ($fleet_route, $date, $layout, $unavailable, $booking, $user_id) {
            $item->is_booking = $booking->where('layout_chair_id', $item->id)->isNotEmpty();
            $item->is_unavailable = $unavailable->filter(function($e) use ($item) {
                return $e->order_detail->where('layout_chair_id', $item->id)->first();
            })->isNotEmpty();
            $item->is_mine = $unavailable->filter(function($e) use ($item, $user_id) {
                return $e->order_detail->where('layout_chair_id', $item->id)->isNotEmpty() && $e->user_id == $user_id && $e->user_id != null;
            })->isNotEmpty();
            return $item;
        });

        return $layout;
    }
}
        