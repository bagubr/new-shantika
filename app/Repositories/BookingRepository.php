<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository {
    public static function isBooked($fleet_route_id, $user_id, int $layout_chair_id, string $date = null) {
        if(empty($date)) $date = date('Y-m-d');
        return Booking::where('fleet_route_id', $fleet_route_id)
            ->where('layout_chair_id', $layout_chair_id)
            ->where('booking_at', 'ilike', '%'.$date.'%')
            ->where('expired_at', '<', date('Y-m-d H:i:s'))
            ->where('user_id', '!=', $user_id)
            ->exists();
    }

    public static function findByCodeBookingWithRouteWithLayoutChair($code_booking)
    {
        return Booking::with(['route', 'chair'])->where('code_booking', $code_booking)->get();
    }

    public static function getTodayByRoute($route_id) {
        return Booking::where('route_id', $route_id)
            ->where('expired_at', '>=', date('Y-m-d H:i:s'))
            ->get();
    }
}
        