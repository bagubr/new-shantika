<?php

namespace App\Services;

use App\Jobs\PaymentLastThirtyMinuteReminderJob;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Utils\Image;
use App\Utils\NotificationMessage;
use DateTime;
use Xendit\Xendit;

class PaymentService {
    public static function createOrderPayment(Order $order, $payment_type_id = null) {
        if($payment_type_id == null) {
            $payment_type_id  = PaymentType::first()->id;
        }
        
        if(empty($payment_type_id) || $payment_type_id == 1) {
            $invoice = Payment::create([
                'order_id'=>$order->id,
                'payment_type_id'=>$payment_type_id,
                'status'=>Payment::STATUS1,
                'secret_key'=>md5(date('Ymdhis')).uniqid(),
                'expired_at'=>date('Y-m-d H:i:s', strtotime('+3 days'))
            ]);
        } else {
            Xendit::setApiKey(env('API_KEY_XENDIT'));
            $payload = [
                'external_id'=>$order->id.uniqid(),
                'payer_email'=>$order->order_detail[0]->email,
                'description'=>'test',
                'amount'=>$order->price,
            ];
            $invoice = \Xendit\Invoice::create($payload);
            $invoice = Payment::create([
                'order_id'=>$order->id,
                'payment_type_id'=>$payment_type_id,
                'status'=>Payment::STATUS1,
                'secret_key'=>$invoice['id'],
                'expired_at'=>date('Y-m-d H:i:s', strtotime($invoice['expiry_date']))
            ]);
            $invoice['xendit'] = self::getSecretAttribute($invoice);
        }

        self::sendNotificationAlmostExpiry($invoice);

        return $invoice;
    }

    public static function sendNotificationAlmostExpiry($invoice) {
        if($invoice->order->status != Order::STATUS1) {
            return;
        }

        $send_at = now()->diffInMinutes(date_create(strtotime($invoice, "-30 minutes")));
        $payload = NotificationMessage::paymentWillExpired();
        $notification = Notification::build($payload[0], $payload[1], Notification::TYPE1, $invoice->order_id);
        PaymentLastThirtyMinuteReminderJob::dispatch($notification, $invoice->order->user->fcm_token, false)
            ->delay(now()->addMinutes($send_at));

        $send_at = now()->diffInMinutes(date_create(strtotime($invoice)));
        $payload = NotificationMessage::paymentExpired(date("d-M-Y", strtotime($invoice->order->reserve_at)));
        $notification = Notification::build($payload[0], $payload[1], Notification::TYPE1, $invoice->order_id);
        PaymentLastThirtyMinuteReminderJob::dispatch($notification, $invoice->order->user->fcm_token, false)
            ->delay(now()->addMinutes($send_at));
    }

    public static function getSecretAttribute(Payment $payment) {
        if($payment->payment_type_id == 1) {
            Xendit::setApiKey(env('API_KEY_XENDIT'));
            return \Xendit\Invoice::retrieve($payment->secret_key)['invoice_url'];
        } else {
            return "";
        }
    }

    public static function uploadProof(Order $order, $file) {
        Image::uploadFile($file, 'proof_order');

        $order->payment()->update([
            'proof'=>$file
        ]);
        OrderService::updateStatus($order,Order::STATUS6);
        $order->refresh();

        return $order;
    }

    public static function updateStatus(Payment|int $payment, $status) {
        if(is_int($payment)) {
            $payment = Payment::find($payment);
        }

        $payment->update([
            'status'=>$status
        ]);

        $payment->order()->update([
            'status'=>$status
        ]);
        $payment->refresh();

        return $payment;
    }

    public static function receiveCallback(Payment $payment, $status) {
        $payment->update([
            'status'=>$status,
            'paid_at'=> date('Y-m-d H:i:s'),
        ]);
        $payment->order()->update([
            'status'=>$status
        ]);
        $payment->refresh();

        return $payment;
    }
}
        