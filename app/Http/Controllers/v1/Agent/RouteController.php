<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\v1\RouteController as BaseRouteController;
use App\Http\Requests\Api\ApiGetAvailableRouteRequest;
use App\Http\Resources\Route\AvailableRoutesResource;
use App\Models\Route;
use App\Repositories\TimeClassificationRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class RouteController extends BaseRouteController
{
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        $agency_id = $user->agencies->id;
        $routes = Route::with(['fleet', 'checkpoints.agency.city', 'checkpoints'=>function($query) {

            }])
            ->whereHas('fleet.fleetclass', function($query) use ($request) { 
                $query->where('fleet_class_id', $request->fleet_class_id);
            })
            ->whereHas('checkpoints', function($query) use ($request, $agency_id) {
                $query->whereRaw('exists (select 1 from checkpoints inner join checkpoints c on c.route_id = checkpoints.route_id
                where (checkpoints.agency_id = ? and c.agency_id = ?) 
                and (c.route_id = routes.id and checkpoints.route_id = routes.id)
                and (checkpoints.order > c.order))', [$request->agency_id, $agency_id]);
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
        
        foreach($routes as $index => $route) {
            $found_destination = false;
            $found_departure = false;
            $checkpoints = $route->checkpoints->filter(function($item, $key) use ($request, &$route, &$found_destination, &$found_departure, $agency_id) {
                //
                if($item->agency_id == $agency_id) {
                    $route->departure_at = $item->arrived_at;
                    $found_departure = true;
                }
                if(!$found_departure && !$found_destination) {
                    return false;
                }


                //
                if($found_destination) {
                    return false;
                }
                if($item->agency_id == $request->agency_id) {
                    $route->arrived_at = $item->arrived_at;
                    $found_destination = true;
                }
                return true;
            });
            unset($route->checkpoints);
            $route->checkpoints = $checkpoints->values();
        }

        return $this->sendSuccessResponse([
            'routes'=>AvailableRoutesResource::collection($routes)
        ]);
    }
}
