<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\ScheduleUnavailableBooking;
use App\Repositories\BookingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ScheduleUnavailableBookingRepository;
use App\Utils\Response;

class BookingService {
    use Response;

    public static function getCurrentExpiredAt() {
        return date('Y-m-d H:i:s', strtotime("+15 minutes"));
    }

    public static function create(Booking $booking) {
        $booking_exists = BookingRepository::isBooked($booking->route_id, $booking->user_id, $booking->layout_chair_id, $booking->booking_at);
        if($booking_exists) {
            (new self)->sendFailedResponse([], 'Maaf, kursi sudah dibooking');
        }

        $schedule_unavailable = ScheduleUnavailableBookingRepository::isBookingNotAllowed(date('Y-m-d H:i:s'));
        if($schedule_unavailable) {
            (new self)->sendFailedResponse([], 'Kamu tidak bisa membooking untuk tanggal ini, tapi masih bisa untuk melakukan pemesanan');
        }

        $order_exists = OrderRepository::isOrderUnavailable($booking->route_id, $booking->booking_at, $booking->layout_chair_id);
        if($order_exists) {
            (new self)->sendFailedResponse([], 'Maaf, kursi sudah dipesan orang lain');
        }

        $booking = Booking::create($booking->toArray());

        return $booking;
    }

    public static function deleteByCodeBooking($code_booking) {
        Booking::where('code_booking', $code_booking)->delete();
    }
}   