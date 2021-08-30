<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Setting;

class BookingRepository {
    public static function isBooked($fleet_route_id, $user_id, int|array $layout_chair_id, string $date = null) {
        if(empty($date)) $date = date('Y-m-d');
        if(is_int($layout_chair_id)) {
            $layout_chair_id = (array) $layout_chair_id;
        }
        $expiry = Setting::first()->booking_expired_duration;
        return Booking::where('fleet_route_id', $fleet_route_id)
            ->whereIn('layout_chair_id', $layout_chair_id)
            ->where('booking_at', 'ilike', '%'.$date.'%')
            ->where('expired_at', '>', date('Y-m-d H:i:s'))
            ->where('user_id', '!=', $user_id)
            ->exists();
    }

    public static function findByCodeBookingWithRouteWithLayoutChair($code_booking)
    {
        return Booking::with(['fleet_route.route', 'chair'])->where('code_booking', $code_booking)->get();
    }

    public static function getTodayByRoute($fleet_route_id) {
        return Booking::where('fleet_route_id', $fleet_route_id)
            ->with('user.agencies.agent')
            ->where('expired_at', '>', date('Y-m-d H:i:s'))
            ->get();
    }
}
        