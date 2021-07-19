<?php

namespace App\Repositories;
use App\Http\Resources\Fleet\InformationFleetResource;
use App\Models\Fleet;

class FleetRepository
{
    public static function all($search = '')
    {
        return Fleet::get();
    }

    public static function allWithRoute($search = '')
    {
        $fleet = Fleet::where('name', 'ilike', '%'.$search.'%')->get();
        return InformationFleetResource::collection($fleet);
    }

    public static function getWithLayout() {
        return Fleet::with('layout')->get();
    }

    public static function deleteId($id)
    {
        return Fleet::withTrashed()->where('id', $id)->firstOrFail();
    }

    public static function getWithRoute($id)
    {
        
    }
}
