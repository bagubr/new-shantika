<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ApiGetAvailableRouteRequest;
use App\Http\Resources\Route\AvailableRoutesResource;
use App\Models\FleetRoute;
use App\Models\Route;
use App\Repositories\AgencyRepository;
use App\Repositories\TimeClassificationRepository;

class RouteController extends Controller
{
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request)
    {
        $max_date = date('Y-m-d', strtotime("+30 days"));
        if ($request->date > $max_date) {
            $this->sendFailedResponse([], 'Kamu tidak bisa memesan untuk tanggal lebih dari ' . $max_date);
        }
        $destination_agency = AgencyRepository::findWithCity($request->agency_arrived_id);
        $departure_agency = AgencyRepository::findWithCity($request->agency_departure_id);

        if (empty($destination_agency->is_active)) {
            return $this->sendFailedResponse([], 'Agen tujuan tidak aktif, mohon coba agen yang lain');
        }
        if (empty($departure_agency->is_active)) {
            return $this->sendFailedResponse([], 'Akun agen keberangkatan anda dinonaktifkan, mohon coba agen yang lain');
        }

        $routes = FleetRoute::with(['route.fleet', 'route.checkpoints.agent.city', 'route.checkpoints'=>function($query) {
                $query->orderBy('order', 'asc');
            }])
            ->where('is_active', true)
            ->whereHas('fleet', function ($query) use ($request) {
                $query->where('fleet_class_id', $request->fleet_class_id);
            })
            ->whereHas('route.checkpoints', function ($query) use ($destination_agency, $departure_agency) {
                $query->where('agency_id', $destination_agency->id)
                    ->where(function ($subquery) use ($destination_agency, $departure_agency) {
                        $subquery->whereHas('agency.city', function ($subsubquery) use ($departure_agency) {
                            $subsubquery->where('area_id', '!=', $departure_agency->city->area_id);
                        })
                    ->where('agency_id', $departure_agency->id);
                });
            })
            ->when(($request->time), function ($que) use ($request) {
                $que->whereHas('route', function ($query) use ($request) {
                    $time_start = TimeClassificationRepository::findByName($request->time)->time_start;
                    $time_end = TimeClassificationRepository::findByName($request->time)->time_end;

                    $query->where('departure_at', '>', $time_start);
                    $query->orWhere('arrived_at', '<', $time_end);
                });
            })
            ->get();

        foreach($routes as $index => $route) {
            $found = false;
            $checkpoints = $route->checkpoints->filter(function($item, $key) use ($request, &$route, &$found) {
                if($found) {
                    return false;
                }
                if($item->agency_id == $request->agency_arrived_id) {
                    $found = true;
                }
                return true;
            });
            unset($route->checkpoints);
            $route->checkpoints = $checkpoints->values();
        }
    
        return $this->sendSuccessResponse([
            'routes' => AvailableRoutesResource::collection($routes)
        ]);;
    }
}
