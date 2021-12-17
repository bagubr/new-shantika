<?php

namespace App\Repositories;

use App\Models\Agency;

class AgencyRepository
{
    public static function all($request = null)
    {
        return Agency::with('users:users.id,users.phone')
        ->when(($request->city_id), function ($query) use ($request)
        {
            $query->where('city_id', $request->city_id);
        })
        ->when($request, function ($query) use ($request) {
            $query->where('name', 'ilike', '%' . $request->search . '%')
                ->orWhereHas('city', function ($subquery) use ($request) {
                    $subquery->where('name', 'ilike', '%' . $request->search . '%');
                });
        })
        ->orderBy('id', 'desc')->get();
    }

    public static function allByCity($request = null)
    {
        return Agency::with('users:users.id,users.phone')
        ->when(($request->city_id), function ($query) use ($request)
        {
            $query->where('city_id', $request->city_id);
        })
        ->when($request, function ($query) use ($request) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        })
        ->orderBy('id', 'desc')->get();
    }

    public static function findWithCity($id) {
        return Agency::with('city')->find($id);
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
        $agency = AgencyRepository::findWithCity($request->agency_id);
        return Agency::when(($request->city_id), function ($q) use ($request) {
                $q->whereHas('city', function ($query) use ($request) {
                    $query->where('id', $request->city_id);
                });
            })
            ->when($request->agency_id, function($q) use ($agency) {
                $q->whereHas('city', function($query) use ($agency) {
                    $query->where('area_id', '!=', $agency->city->area_id);
                });
            })
            ->orderBy('name')->get();
    }

    public static function findByToken($token) {
        $user = UserRepository::findByToken($token);
        $agent = $user->agencies->agent;
        return $agent;
    }
}
