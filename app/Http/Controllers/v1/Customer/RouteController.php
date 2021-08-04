<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ApiGetAvailableRouteRequest;
use App\Http\Resources\Route\AvailableRoutesResource;
use App\Models\Route;
use App\Repositories\TimeClassificationRepository;

class RouteController extends Controller
{
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request) {
        $routes = Route::with(['fleet', 'checkpoints.agency.city'])
        ->whereHas('fleet', function($query) use ($request) {
            $query->when(($request->fleet_class_id), function ($q) use ($request) { 
                $q->where('fleet_class_id', $request->fleet_class_id);
            });
        })
        ->whereHas('checkpoints', function($query) use ($request) {
            $query->when(($request->agency_departure_id && $request->agency_arrived_id), function ($q) use ($request) { 
                    $q->where(function($subquery) use ($request) {
                        $subquery->where('agency_id', $request->agency_arrival_id)
                            ->orWhere('agency_id', $request->agency_departure_id);
                    })
                    ->orderBy('order', 'desc');
                });
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
