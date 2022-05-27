<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index() {
        return $this->sendSuccessResponse([
            'payment_types'=>PaymentType::all()
        ]);
    }
}
