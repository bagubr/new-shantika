<?php

namespace App\Repositories;

use App\Models\Route;

class RoutesRepository
{
    public static function all()
    {
        return Route::all();
    }
    public static function getIdName()
    {
        return Route::all(['id', 'name']);
    }
}
