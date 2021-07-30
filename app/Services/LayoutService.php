<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Layout;
use App\Models\Order;
use App\Models\Route;
use App\Repositories\BookingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;

class LayoutService {
    public static function getAvailibilityChairs(Layout $layout, Route $route, $date = null) {
        if(empty($date)) {
            $date = date('Y-m-d');
        }
        $layout->chairs = $layout->chairs->map(function ($item) use ($route, $date) {
            $item->is_booking = BookingRepository::isTodayExistByLayoutChairByRoute($date, $item->id, $route->id);
            $item->is_unavailable = OrderRepository::isExistAtDateByLayoutChair($date, $item->id);
            $item->is_mine = OrderRepository::isExistAtDateByLayoutChair($date,$item->id, UserRepository::findByToken(request()->bearerToken())?->id);
            return $item;
        });

        return $layout;
    }
}
        