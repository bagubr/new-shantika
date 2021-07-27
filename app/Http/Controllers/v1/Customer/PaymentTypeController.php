<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PaymentType;

class PaymentTypeController extends Controller
{
    public function getPaymentType()
    {
        return $this->sendSuccessResponse([
            'payment_type'=>PaymentType::get()
        ]);
    }
}
