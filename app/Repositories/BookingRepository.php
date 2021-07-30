<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository {
    public static function isBooked(int $layout_chair_id, string $date = null) {
        if(empty($date)) $date = date('Y-m-d');
        return Booking::where('layout_chair_id', $layout_chair_id)
            ->where('created_at', 'ilike', '%'.$date.'%')
            ->exists();
    }

    public static function findByCodeBookingWithRouteWithLayoutChair($code_booking)
    {
        return Booking::with(['route', 'layout_chair'])->where('code_booking', $code_booking)->get();
    }

    public static function isTodayExistByLayoutChairByRoute($date, $layout_chair_id, $route_id) {
        return Booking::where('route_id', $route_id)
            ->where('layout_chair_id', $layout_chair_id)
            ->where('expired_at', '>=', date('Y-m-d H:i:s'))
            ->exists();
    }
}
        