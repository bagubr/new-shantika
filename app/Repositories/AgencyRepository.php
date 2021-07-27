<?php

namespace App\Repositories;

use App\Models\Agency;

class AgencyRepository
{
    public static function all()
    {
        return Agency::orderBy('id', 'desc')->get();
    }

    public static function getWithCity($request)
    {
        return Agency::where('name', 'ilike', '%'.$request->search.'%')->whereHas('city', function ($query) use ($request)
        {
            $query->orWhere('name', 'ilike', '%'.$request->search.'%');
        })->get();
    }
}
