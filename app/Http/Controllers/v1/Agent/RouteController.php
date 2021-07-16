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
                $query->where('fleet_class_id', $request->fleet_class_id);
            })
            ->whereHas('checkpoints', function($query) use ($request) {
                $query->where('agency_id', $request->agency_id)->orderBy('order', 'desc');
            })
            ->where(function($query) use ($request) {
                $query->where('departure_at', '>=', $request->time_start)
                    ->where('departure_at', '<=', $request->time_end);
            })
            ->get();

        return $this->sendSuccessResponse([
            'routes'=>AvailableRoutesResource::collection($routes)
        ]);
    }
}
