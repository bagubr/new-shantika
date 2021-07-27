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

    public static function findWithRouteWithLayoutChair($id)
    {
        return Booking::with(['route', 'layout_chair'])->find($id);
    }
}
        