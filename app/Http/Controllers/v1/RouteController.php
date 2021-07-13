<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiGetAvailableRouteRequest;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request) {
        $routes = Route::with(['fleet', 'city'])
            ->whereHas('fleet', function($query) use ($request) {
                $query->where('fleet_class_id', $request->fleet_class_id);
            })
            ->whereHas('checkpoints.agency', function($query) use ($request) {
                $query->where('id', $request->agency_id);
            })
            ->where(function($query) use ($request) {
                $query->where('departure_at', '>=', $request->time_start)
                    ->where('departure_at', '<=', $request->time_end);
            })
            ->get();

        return response($routes);
    }
}
