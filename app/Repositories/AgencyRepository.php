<?php

namespace App\Repositories;

use App\Models\Agency;

class AgencyRepository
{
    public static function all($search = null)
    {
        return Agency::with('users:users.id,users.phone')->when($search, function($query) use ($search) {
            $query->where('name', 'ilike', '%'.$search.'%')
                ->orWhereHas('city', function($subquery) use ($search) {
                    $subquery->where('name', 'ilike', '%'.$search.'%');
                });
        })->orderBy('id', 'desc')->get();
    }

    public static function getOnlyIdName()
    {
        return Agency::get(['id', 'name']);
    }

    public static function getWithCity($request)
    {
        return Agency::with('users:users.id,users.phone')->where('name', 'ilike', '%' . $request->search . '%')->whereHas('city', function ($query) use ($request) {
            $query->orWhere('name', 'ilike', '%' . $request->search . '%');
        })->get();
    }
}
