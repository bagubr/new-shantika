<?php

namespace App\Repositories;

use App\Models\Information;

class InformationRepository
{
    public static function all()
    {
        return Information::all();
    }
}
