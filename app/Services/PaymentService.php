<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentType;
use Xendit\Xendit;

class PaymentService {
    public static function createOrderPayment(Order $order, $payment_type_id = null) {
        if($payment_type_id == null) {
            $payment_type_id  = PaymentType::first()->id;
        }
        
        if(empty($payment_type_id)) {
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
        }

        return $invoice;
    }

    public static function getSecretAttribute(Payment $payment) {
        if($payment->payment_type_id == 1) {
            Xendit::setApiKey(env('API_KEY_XENDIT'));
            return \Xendit\Invoice::retrieve($payment->secret_key)['invoice_url'];
        }
    }
}
        