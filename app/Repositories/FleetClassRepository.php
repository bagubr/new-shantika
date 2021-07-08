<?php

namespace App\Repositories;

use App\Models\FleetClass;

class FleetClassRepository
{
    public static function all()
    {
        return FleetClass::all();
    }
    public static function deleteId($id)
    {
        return FleetClass::withTrashed()->where('id', $id)->firstOrFail();
    }
}
