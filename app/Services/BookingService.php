<?php

namespace App\Services;

use App\Events\SendingNotification;
use App\Jobs\BookingExpiryReminderJob;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\ScheduleUnavailableBooking;
use App\Models\Setting;
use App\Repositories\BookingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ScheduleUnavailableBookingRepository;
use App\Utils\NotificationMessage;
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

        self::sendNotificationAtExpiry($booking);

        return $booking;
    }

    private static function sendNotificationAtExpiry($booking) {
        $chairs = Booking::with('layout_chair:id,name')->where('code_booking', $booking->code_booking)->get();
        if($chairs[0]->id != $booking->id) {
            return;
        }
        $payload = NotificationMessage::bookingExpired($chairs->pluck('layout_chair.name')->values()->toArray());
        $notification = Notification::build($payload[0], $payload[1], Notification::TYPE1, $booking->id, $booking->user_id);
        $expired_time = Setting::first()->booking_expired_duration;
        BookingExpiryReminderJob::dispatch($notification, $booking->user->fcm_token, false)->delay(now()->addMinutes($expired_time));
    }

    public static function deleteByCodeBooking($code_booking) {
        Booking::where('code_booking', $code_booking)->delete();
    }
}   