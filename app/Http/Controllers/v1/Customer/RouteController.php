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
        // return 'oke';
        $routes = Route::with(['fleet', 'checkpoints.agency.city'])
            ->whereHas('fleet', function($query) use ($request) {
                $query->when(($request->fleet_class_id), function ($q) use ($request) { 
                    $q->where('fleet_class_id', $request->fleet_class_id);
                });
            })
            ->whereHas('checkpoints', function($query) use ($request) {
                $query->when(($request->agency_id), function ($q) use ($request) { 
                    $q->where('agency_id', $request->agency_departure_id)->orderBy('order', 'desc');
                    $q->orWhere('agency_id', $request->agency_arrived_id)->orderBy('order', 'desc');
                });
            })
            ->when(($request->time), function ($q) use ($request) { 
                $time_start = TimeClassificationRepository::findByName($request->time)->time_start;
                $time_end = TimeClassificationRepository::findByName($request->time)->time_end;
                $q->where('departure_at', '>', $time_start)
                    ->where('arrived_at', '<', $time_end);
            })
            ->get();

        return $this->sendSuccessResponse([
            'routes'=>AvailableRoutesResource::collection($routes)
        ]);
    }
}
