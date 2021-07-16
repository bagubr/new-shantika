<?php

namespace App\Repositories;

use App\Models\Fleet;

class FleetRepository
{
    public static function all($search)
    {
        return Fleet::all();
    }

    public static function allWithSearch($search = '')
    {
        return Fleet::where('name', 'ilike', '%'.$search.'%')->get();
    }

    public static function getWithLayout() {
        return Fleet::with('layout')->get();
    }

    public static function deleteId($id)
    {
        return Fleet::withTrashed()->where('id', $id)->firstOrFail();
    }
}
