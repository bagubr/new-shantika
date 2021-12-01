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

    public static function allWithRoute($search = '', $fleet_class_id = null)
    {
        $fleet = Fleet::where('name', 'ilike', '%'.$search.'%')
        ->when($fleet_class_id, function ($query) use ($fleet_class_id)
        {
            $query->where('fleet_class_id', $fleet_class_id);
        })
        ->get();
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
