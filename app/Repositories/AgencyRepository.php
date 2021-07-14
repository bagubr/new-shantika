<?php

namespace App\Repositories;

use App\Models\Agency;

class AgencyRepository
{
    public static function all()
    {
        return Agency::orderBy('id', 'desc')->get();
    }
}
