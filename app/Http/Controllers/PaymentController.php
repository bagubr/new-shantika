<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Jobs\PaymentAcceptedNotificationJob;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Setting;
use App\Repositories\PaymentTypeRepository;
use App\Services\MembershipService;
use App\Services\OrderPriceDistributionService;
use App\Utils\NotificationMessage;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->status;
        $payment_type_id = $request->payment_type_id;

        $payment_types = PaymentType::all();
        $payments = Payment::when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })->when($payment_type_id, function ($q) use ($payment_type_id) {
            $q->where('payment_type_id', $payment_type_id);
        })->orderBy('id', 'desc')->paginate(10);

        $statuses = [Payment::STATUS1, Payment::STATUS2, Payment::STATUS3];
        $test = $request->flash();

        return view('payment.index', compact('payments', 'payment_types', 'statuses', 'test'));
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
        $statuses = ['PAID', 'DECLINED'];
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
        $data = $request->only('status', 'proof_decline_reason', 'paid_at');
        $order_id = Order::where('id', $payment->order_id)->first();
        if ($request->hasFile('proof')) {
            $proof = $request->proof->store('proof', 'public');
            $payment->deleteProof();
            $data['proof'] = $proof;
        };
        $payment->update($data);
        $order_id->update([
            'status' => $request->status,
        ]);
        if ($request->status == Order::STATUS3) {
            $total_price = OrderPriceDistributionService::calculateDistribution($order_id, $order_id->order_detail, $order_id->distribution->ticket_only);
            $order_id->distribution()->update([
                'for_agent' => $total_price['for_agent'],
                'for_owner' => $total_price['for_owner'],
                'for_owner_with_food' => $total_price['for_owner_with_food'],
                'for_owner_gross' => $total_price['for_owner_gross']
            ]);
            $payload = NotificationMessage::paymentSuccess($order_id->code_order);
            $notification = Notification::build(
                $payload[0],
                $payload[1],
                Notification::TYPE1,
                $order_id->id,
                $order_id->user_id
            );
            $membership = Membership::where('user_id', $order_id->user_id)->first();
            if($membership){
                MembershipService::increment($membership, Setting::find(1)->point_purchase, 'Pembelian Tiket');
            }
            PaymentAcceptedNotificationJob::dispatchAfterResponse($notification, $order_id->user?->fcm_token, true);
        } else if ($request->status == Order::STATUS7) {
            $payload = NotificationMessage::paymentDeclined($order_id->code_order, $order_id->payment->proof_decline_reason);
            $notification = Notification::build(
                $payload[0],
                $payload[1],
                Notification::TYPE1,
                $order_id->id,
                $order_id->user_id
            );
            PaymentAcceptedNotificationJob::dispatchAfterResponse($notification, $order_id->user?->fcm_token, true);
        }
        session()->flash('success', 'Pembayaran Berhasil Diperbarui');
        return redirect(route('payment.index'));
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
