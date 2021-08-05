<?php

namespace App\Repositories;

use App\Models\Agency;

class AgencyRepository
{
    public static function all($search = null)
    {
        return Agency::with('users:users.id,users.phone')->when($search, function ($query) use ($search) {
            $query->where('name', 'ilike', '%' . $search . '%')
                ->orWhereHas('city', function ($subquery) use ($search) {
                    $subquery->where('name', 'ilike', '%' . $search . '%');
                });
        })->orderBy('id', 'desc')->get();
    }

    public static function getOnlyIdName()
    {
        return Agency::get(['id', 'name']);
    }
    public static function all_order()
    {
        return Agency::orderBy('city_id', 'asc')->get();
    }

    public static function getWithCity($request)
    {
        return Agency::when(($request->city_id), function ($q) use ($request) {
            $q->whereHas('city', function ($query) use ($request) {
                $query->where('id', $request->city_id);
            });
        })
            ->orderBy('name')->get();
    }
}
