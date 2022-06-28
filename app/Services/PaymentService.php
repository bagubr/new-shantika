<?php

namespace App\Services;

use App\Events\SendingNotification;
use App\Jobs\PaymentExpiredReminderJob;
use App\Jobs\PaymentLastThirtyMinuteReminderJob;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Setting;
use App\Utils\Image;
use App\Utils\NotificationMessage;
use DateTime;
use Xendit\Xendit;
use App\Utils\Response;

class PaymentService
{
    use Response;
    public static function createOrderPayment(Order $order, $payment_type_id = null)
    {
<<<<<<< HEAD
        $expired_duration = self::getExpiredDuration(Setting::find(1)->time_expired, $order->reserve_at);
=======
        $expired_duration = self::getExpiredDuration(Setting::find(1)->time_expired);
>>>>>>> rilisv1
        if ($payment_type_id == null) {
            $payment_type_id  = PaymentType::first()->id;
        }

        if ($expired_duration < 0) {
            (new self)->sendFailedResponse([], 'Waktu Pemesanan sudah terlewati');
        }

        if (empty($payment_type_id) || $payment_type_id == 2) {
            $date = time() + $expired_duration;
            $invoice = Payment::create([
                'order_id' => $order->id,
                'payment_type_id' => $payment_type_id,
                'status' => Payment::STATUS1,
                'secret_key' => md5(date('Ymdhis')) . uniqid(),
                'expired_at' => date('Y-m-d H:i:s', $date)
            ]);
        } else {
            Xendit::setApiKey(env('API_KEY_XENDIT'));
            $payload = [
                'external_id' => $order->id . uniqid(),
                'payer_email' => 'devnewshantika@gmail.com',
                'description' => 'Pembayaran Tiket Armada',
                'amount' => $order->price,
                'invoice_duration' => $expired_duration
            ];
            $invoice = \Xendit\Invoice::create($payload);
            $invoice = Payment::create([
                'order_id' => $order->id,
                'payment_type_id' => $payment_type_id,
                'status' => Payment::STATUS1,
                'secret_key' => $invoice['id'],
                'expired_at' => date('Y-m-d H:i:s', strtotime($invoice['expiry_date']))
            ]);
            $invoice['xendit'] = self::getSecretAttribute($invoice);
        }

        self::sendNotificationAlmostExpiry($invoice);

        return $invoice;
    }

    public static function sendNotificationAlmostExpiry($invoice)
    {
<<<<<<< HEAD
        if ($invoice->order->status != Order::STATUS1) {
            return;
        }

        $time = strtotime($invoice->expired_at) - (30 * 60);
        $send_at = now()->diffInMinutes(date('Y-m-d H:i:s', $time));
        $payload = NotificationMessage::paymentWillExpired();
        $notification = Notification::build($payload[0], $payload[1], Notification::TYPE1, $invoice->order_id);
        PaymentLastThirtyMinuteReminderJob::dispatch($notification, $invoice->order?->user?->fcm_token, false)
            ->delay(now()->addMinutes(2));

        $time = strtotime($invoice->expired_at);
        $send_at = now()->diffInMinutes(date('Y-m-d H:i:s', $time));
        $payload = NotificationMessage::paymentExpired(date("d-M-Y", strtotime($invoice->order->reserve_at)));
        $notification = Notification::build($payload[0], $payload[1], Notification::TYPE1, $invoice->order_id);
        PaymentExpiredReminderJob::dispatch($notification, $invoice->order?->user?->fcm_token, false)
            ->delay(now()->addMinutes(5));
=======
        // if ($invoice->order->status != Order::STATUS1) {
        //     return;
        // }

        // $time = strtotime($invoice->expired_at) - (30 * 60);
        // $send_at = now()->diffInMinutes(date('Y-m-d H:i:s', $time));
        // $payload = NotificationMessage::paymentWillExpired();
        // $notification = Notification::build($payload[0], $payload[1], Notification::TYPE1, $invoice->order_id);
        // PaymentLastThirtyMinuteReminderJob::dispatchIf(self::paymentStatus($invoice->order->id), $notification, $invoice->order?->user?->fcm_token, false, $invoice->order->id)->delay(now()->addMinutes(2));

        // $time = strtotime($invoice->expired_at);
        // $payload = NotificationMessage::paymentExpired(date("d-M-Y", strtotime($invoice->order->reserve_at)));
        // $notification = Notification::build($payload[0], $payload[1], Notification::TYPE1, $invoice->order_id);
        // PaymentExpiredReminderJob::dispatch($notification, $invoice->order?->user?->fcm_token, false, $invoice->order->id)
        //     ->delay(now()->addMinutes(date('i', strtotime(Setting::find(1)->time_expired))));
    }

    public static function paymentStatus($id)
    {
        $order = Order::find($id);
        if($order->status == Order::STATUS1){
            return true;
        }else{
            return false;
        }
>>>>>>> rilisv1
    }

    public static function getSecretAttribute(Payment $payment)
    {
        if ($payment->payment_type_id == 1) {
            Xendit::setApiKey(env('API_KEY_XENDIT'));
            return \Xendit\Invoice::retrieve($payment->secret_key)['invoice_url'];
        } else {
            return "";
        }
    }

    public static function uploadProof(Order $order, $file)
    {
        Image::uploadFile($file, 'proof_order');

        $order->payment()->update([
            'proof' => $file
        ]);
        OrderService::updateStatus($order, Order::STATUS6);
        $order->refresh();

        return $order;
    }

    public static function updateStatus(Payment|int $payment, $status)
    {
        if (is_int($payment)) {
            $payment = Payment::find($payment);
        }

        $payment->update([
            'status' => $status
        ]);

        $payment->order()->update([
            'status' => $status
        ]);
        $payment->refresh();

        return $payment;
    }

    public static function receiveCallback(Payment $payment, $status)
    {
        $payment->update([
            'status' => $status,
            'paid_at' => date('Y-m-d H:i:s'),
        ]);
        $payment->order()->update([
            'status' => $status
        ]);
        $payment->refresh();

        if ($status == Order::STATUS3) {
            $message = NotificationMessage::paymentSuccess();
            $notification = Notification::build(
                $message[0],
                $message[1],
                Notification::TYPE1,
                $payment->order->id,
                $payment->order->user_id
            );
            SendingNotification::dispatch($notification, $payment->order->user?->fcm_token, true);
        }

        return $payment;
    }

<<<<<<< HEAD
    public static function getExpiredDuration($time, $reserve_at)
=======
    public static function getExpiredDuration($time)
>>>>>>> rilisv1
    {
        // Menentukan Expired dari menentukan jam dan menit pada hari reservesi
        // $hour = date("H", strtotime($time));
        // $minute = date("i", strtotime($time));
        // $date = new Datetime($reserve_at);
        // $date1 = $date->format('Y-m-d H:i:s');
        // $date2 = $date->setTime($hour, $minute)->format('Y-m-d H:i:s');
        // $start = new DateTime($date1);
        // $end = new DateTime($date2);
        // $interval = $end->getTimestamp() - $start->getTimestamp();
        // return $interval;
        // $time = '02:02';

        // Menentukan Expired dari penambahan jam dan menit
        $time2_arr = [];
        $time2_arr = explode(":", $time);
        $interval = ($time2_arr[0] * 60 * 60) + ($time2_arr[1] * 60);
        return $interval;
    }
}
