<?php

namespace App\Repositories;

use App\Models\FleetClass;

class FleetClassRepository
{
    public static function all()
    {
        return FleetClass::all();
    }
}
