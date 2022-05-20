<?php

namespace App\Repositories;

use App\Models\SouvenirRedeem;

class SouvenirReedemRepository {

    public static function getByUserId($membership_id)
    {
        return SouvenirRedeem::where('membership_id', $membership_id)->orderBy('id', 'desc')->get();
    }
}
        