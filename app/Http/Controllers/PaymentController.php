<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Jobs\PaymentAcceptedNotificationJob;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\PaymentTypeRepository;
use App\Utils\NotificationMessage;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();
        return view('payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        $payment_types = PaymentTypeRepository::all();
        $test = strtotime($payment->paid_at);
        $time = date('Y-m-d', $test);
        $statuses = ['PAID', 'WAITING_CONFIRMATION', 'DECLINED'];
        return view('payment.create', compact('payment', 'payment_types', 'statuses', 'time'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $data = $request->only('payment_type_id', 'status', 'proof_decline_reason', 'paid_at');
        $order_id = Order::where('id', $payment->order_id);
        if ($request->hasFile('proof')) {
            $proof = $request->proof->store('proof', 'public');
            $payment->deleteProof();
            $data['proof'] = $proof;
        };
        $payment->update($data);
        $order_id->update([
            'status' => $request->status,
        ]);
        // $order_id->payment()->update([
        //     'status' => $request->status
        // ]);
        if ($request->status == Order::STATUS3) {
            $payload = NotificationMessage::paymentSuccess($order_id->code_order);
            $notification = Notification::build(
                $payload[0],
                $payload[1],
                Notification::TYPE1,
                $order_id->id
            );
            PaymentAcceptedNotificationJob::dispatchAfterResponse($notification, $order_id->user?->fcm_token, true);
        } else if ($request->status == Order::STATUS7) {
            $payload = NotificationMessage::paymentDeclined($order_id->code_order, $order_id->payment->proof_decline_reason);
            $notification = Notification::build(
                $payload[0],
                $payload[1],
                Notification::TYPE1,
                $order_id->id
            );
            PaymentAcceptedNotificationJob::dispatchAfterResponse($notification, $order_id->user?->fcm_token, true);
        }
        session()->flash('success', 'Pembayaran Berhasil Diperbarui');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->deleteProof();
        $payment->delete();
        session()->flash('success', 'Pembayaran Berhasil Dihapus');
        return redirect(route('payment.index'));
    }
}
