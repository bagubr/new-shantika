<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository
{
    public static function all()
    {
        return City::all();
    }
}
