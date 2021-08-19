<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\v1\RouteController as BaseRouteController;
use App\Http\Requests\Api\ApiGetAvailableRouteRequest;
use App\Http\Resources\Route\AvailableRoutesResource;
use App\Models\Agency;
use App\Models\FleetRoute;
use App\Models\Route;
use App\Repositories\AgencyRepository;
use App\Repositories\TimeClassificationRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class RouteController extends BaseRouteController
{
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request) {
        $max_date = date('Y-m-d', strtotime("+30 days"));
        if($request->date > $max_date) {
            $this->sendFailedResponse([], 'Kamu tidak bisa memesan untuk tanggal lebih dari '.$max_date);
        }
        $destination_agency = AgencyRepository::findWithCity($request->agency_id);
        $routes = $routes = FleetRoute::with(['route.fleet', 'route.checkpoints.agency.city'])
            ->whereHas('fleet', function($query) use ($request) { 
                $query->where('fleet_class_id', $request->fleet_class_id);
            })
            ->whereHas('route', function($query) use ($destination_agency) {
                $query->where('destination_city_id', $destination_agency->city_id);
            })
            ->when(($request->time), function ($que) use ($request) {
                $que->whereHas('route', function($query) use ($request){
                    $time_start = TimeClassificationRepository::findByName($request->time)->time_start;
                    $time_end = TimeClassificationRepository::findByName($request->time)->time_end;

                    $query->where('departure_at', '>', $time_start);
                    $query->orWhere('arrived_at', '<', $time_end);
                });
            })
            ->get();

        return $this->sendSuccessResponse([
            'routes'=>AvailableRoutesResource::collection($routes)
        ]);
    }
}
