<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Repositories\PaymentRepository;
use App\Services\MembershipService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function callbackXendit(Request $request)
    {
        // if($request->header('X-CALLBACK-TOKEN') != env('HEADER_XENDIT')) {
        //     abort(404);
        // }

        $payment = PaymentRepository::findBySecret($request->id);

        $payment = PaymentService::receiveCallback($payment, $request->status);
        try {
            $code_member = $payment?->order?->id_member;
            if($request->status == Order::STATUS3){
                MembershipService::increment(Membership::where('code_member', $code_member)->first(), Setting::find(1)->point_purchase * count($payment?->order?->order_detail), 'Pembelian Tiket');
            }
        } catch (\Throwable $th) {

        }

        return $this->sendSuccessResponse([
            'payment' => $payment
        ]);
    }

    public function ChangeOrderStatus()
    {

        DB::beginTransaction();
        try {
            $orders = Order::with('payment')->where('status', Order::STATUS1)->whereHas('payment', function ($q) {
                $q->where('payment_type_id', 2);
            })->where('expired_at', '<', Carbon::now())->get();
            if ($orders->count() <= 0) {
                return $this->sendFailedResponse([], 'Data Tidak Ditemukan');
            }

            foreach ($orders as $order) {
                $payment = Payment::where('order_id', $order->id)->first();
                $order->update([
                    'status' => Order::STATUS2,
                    'cancelation_reason' => 'Waktu Pembayaran Telah Habis'

                ]);
                $payment->update([
                    'status' => Payment::STATUS2,
                    'cancelation_reason' => 'Waktu Pembayaran Telah Habis'
                ]);
            }
            DB::commit();
            return $this->sendSuccessResponse([
                'order' => $order
            ], 'Data Pembayaran Berhasil Di Update');
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}