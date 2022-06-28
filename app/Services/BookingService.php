<?php

namespace App\Services;

use App\Jobs\BookingExpiryReminderJob;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Setting;
<<<<<<< HEAD
=======
use App\Notifications\BookingDelayNotification;
>>>>>>> rilisv1
use App\Repositories\BookingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ScheduleUnavailableBookingRepository;
use App\Utils\NotificationMessage;
use App\Utils\Response;
<<<<<<< HEAD
=======
use Carbon\Carbon;
>>>>>>> rilisv1

class BookingService {
    use Response;

    public static function getCurrentExpiredAt() {
        $setting = Setting::first()->booking_expired_duration;
        return date('Y-m-d H:i:s', strtotime("+ ".$setting." min"));
    }

    public static function create(Booking $booking) {
        $booking_exists = BookingRepository::isBooked($booking->fleet_route_id, $booking->user_id, $booking->layout_chair_id, $booking->booking_at);
        if($booking_exists) {
            (new self)->sendFailedResponse([], 'Maaf, kursi sudah dibooking');
        }

        $schedule_unavailable = ScheduleUnavailableBookingRepository::isBookingNotAllowed(date('Y-m-d H:i:s'));
        if($schedule_unavailable) {
            (new self)->sendFailedResponse([], 'Kamu tidak bisa membooking untuk tanggal ini, tapi masih bisa untuk melakukan pemesanan');
        }

        $order_exists = OrderRepository::isOrderUnavailable($booking->fleet_route_id, $booking->booking_at, $booking->layout_chair_id);
        if($order_exists) {
            (new self)->sendFailedResponse([], 'Maaf, kursi sudah dipesan orang lain');
        }

        $booking->expired_at = self::getCurrentExpiredAt();
        $booking = Booking::create($booking->toArray());

        self::sendNotificationAtExpiry($booking);

        return $booking;
    }

    private static function sendNotificationAtExpiry($booking) {
        $chairs = Booking::with('layout_chair:id,name')->where('code_booking', $booking->code_booking)->get();
        if($chairs[0]->id != $booking->id) {
            return;
        }
        $payload = NotificationMessage::bookingExpired($chairs->pluck('layout_chair.name')->values()->toArray(), $booking->fleet_route?->fleet_detail?->fleet?->name);
        $notification = new Notification([
            "title"=>$payload[0],
            "body"=>$payload[1],
            "type"=>Notification::TYPE1,
            "reference_id"=>$booking->id,
            "user_id"=>$booking->user_id
        ]);
        $expired_time = Setting::first()->booking_expired_duration;
<<<<<<< HEAD
        BookingExpiryReminderJob::dispatch($notification, $booking->user->fcm_token, false)->delay(now()->addMinutes($expired_time));
=======
        BookingExpiryReminderJob::dispatch($notification, $booking->user->fcm_token, false)->delay(Carbon::now()->addMinutes($expired_time));
>>>>>>> rilisv1
    }

    public static function deleteByCodeBooking($code_booking) {
        Booking::where('code_booking', $code_booking)->delete();
    }
}   