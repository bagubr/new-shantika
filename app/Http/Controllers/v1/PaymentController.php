<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function callbackXendit(Request $request) {
        $payment = PaymentRepository::findBySecret($request->id);

        $payment->update([
            'status'=>$request->status
        ]);
        $payment->order()->update([
            'status'=>$request->status
        ]);
        $payment->refresh();

        return $this->sendSuccessResponse([
            'payment'=>$payment
        ]);
    }
}
