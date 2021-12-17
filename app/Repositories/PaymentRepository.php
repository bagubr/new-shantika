<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository {
    public static function findBySecret($secret) {
        return Payment::where('secret_key', $secret)->first();
    }
}
        