<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Utils\Response;

class BookingService {
    use Response;

    public static function getCurrentExpiredAt() {
        return date('Y-m-d H:i:s', strtotime("+15 minutes"));
    }

    public static function create(Booking $booking) {
        if(BookingRepository::isBooked($booking->layout_chair_id, date('Y-m-d'))) {
            return (new self)->sendFailedResponse([], 'Mohon maaf, kursi sudah dibooking');
        }
        $booking = Booking::create($booking->toArray());

        return $booking;
    }
}   