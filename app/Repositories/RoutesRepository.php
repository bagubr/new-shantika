<?php

namespace App\Repositories;

use App\Models\Route;

class RoutesRepository
{
    public static function all()
    {
        return Route::all();
    }
}
