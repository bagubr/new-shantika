<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function callbackXendit(Request $request) {
        // if($request->header('X-CALLBACK-TOKEN') != env('HEADER_XENDIT')) {
        //     abort(404);
        // }

        $payment = PaymentRepository::findBySecret($request->id);

        $payment = PaymentService::receiveCallback($payment, $request->status);
        
        return $this->sendSuccessResponse([
            'payment'=>$payment
        ]);
    }
}
