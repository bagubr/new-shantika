<?php

namespace App\Repositories;

use App\Models\PaymentType;

class PaymentTypeRepository
{
    public static function all()
    {
        return PaymentType::all();
    }
}
