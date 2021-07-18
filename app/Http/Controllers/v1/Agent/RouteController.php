<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\RouteController as BaseRouteController;
use App\Http\Requests\Api\ApiGetAvailableRouteRequest;
use App\Http\Resources\Route\AvailableRoutesResource;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends BaseRouteController
{
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request) {
        $routes = Route::with(['fleet', 'checkpoints.agency.city'])
            ->whereHas('fleet', function($query) use ($request) {
                $query->when(($request->fleet_class_id), function ($q) use ($request) { 
                    $q->where('fleet_class_id', $request->fleet_class_id);
                });
            })
            ->whereHas('checkpoints', function($query) use ($request) {
                $query->where('agency_id', $request->agency_id)->orderBy('order', 'desc');
            })
            ->when(($request->time), function ($q) use ($request) { 
                $time_start = TimeClassificationRepository::findByName($request->time)->time_start;
                $time_end = TimeClassificationRepository::findByName($request->time)->time_end;
                $q->where(function ($que) use ($time_start, $time_end)
                {
                    $que->where('departure_at', '>', $time_start);
                    $que->orWhere('arrived_at', '<', $time_end);
                });
            })
            ->get();

        return $this->sendSuccessResponse([
            'routes'=>AvailableRoutesResource::collection($routes)
        ]);
    }
}
