<?php

namespace App\Repositories;

use App\Models\Fleet;

class FleetRepository
{
    public static function all()
    {
        return Fleet::all();
    }
    public static function deleteId($id)
    {
        return Fleet::withTrashed()->where('id', $id)->firstOrFail();
    }
}
