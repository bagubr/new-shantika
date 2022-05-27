<?php

namespace App\Repositories;

use App\Models\ScheduleUnavailableBooking;

class ScheduleUnavailableBookingRepository {
    public static function isBookingNotAllowed($date) {
        return ScheduleUnavailableBooking::where('start_at', '<=', $date)
        ->where('end_at', '>=', $date)
        ->exists();
    }
}
        