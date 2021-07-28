<?php

namespace App\Repositories;

use App\Http\Resources\Layout\LayoutResource;
use App\Models\Layout;
use App\Models\Route;
use App\Services\LayoutService;
use Database\Seeders\LayoutSeeder;

class LayoutRepository
{
    public static function all()
    {
        return Layout::all();
    }

    public static function findWithChairs($id)
    {
        return Layout::with('chairs')->find($id);
    }
    
    public static function findByRoute(Route $route)
    {
        $layout = Layout::with('chairs')->find($route->fleet->layout_id);

        return $layout;
    }

    public static function latestWithChairs()
    {
        return Layout::with('chairs')->orderBy('id', 'desc')->first();
    }

    public static function paginateWithChairs($paginate = 20)
    {
        return Layout::with('chairs')->orderBy('id', 'desc')->paginate($paginate);
    }
}
