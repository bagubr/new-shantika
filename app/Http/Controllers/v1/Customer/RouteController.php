<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ApiGetAvailableRouteRequest;
use App\Http\Resources\Route\AvailableRoutesResource;
use App\Models\FleetRoute;
use App\Models\Route;
use App\Models\TimeClassification;
use App\Repositories\AgencyRepository;
use App\Repositories\TimeClassificationRepository;
use Illuminate\Database\Eloquent\Builder;

class RouteController extends Controller
{
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request)
    {
        $destination_agency = AgencyRepository::findWithCity($request->agency_arrived_id);
        $departure_agency = AgencyRepository::findWithCity($request->agency_departure_id);
        $date = $request->date;

        if (empty($destination_agency->is_active)) {
            return $this->sendFailedResponse([], 'Agen tujuan tidak aktif, mohon coba agen yang lain');
        }
        if (empty($departure_agency->is_active)) {
            return $this->sendFailedResponse([], 'Akun agen keberangkatan anda dinonaktifkan, mohon coba agen yang lain');
        }

        $routes = FleetRoute::with(['fleet_detail.fleet.layout', 'route.checkpoints.agency.city', 'route.checkpoints.agency.prices'=>function($query) {
            $query->orderBy('id', 'desc');
        }, 'fleet_detail.fleet.fleetclass.prices'])
        ->where('is_active', true)
        ->whereHas('fleet_detail', function($query) use ($request) {
            $query->where('time_classification_id', $request->time_classification_id);
        })
        ->whereHas('fleet_detail.fleet', function ($query) use ($request) {
            $query->where('fleet_class_id', $request->fleet_class_id);
        })
        ->whereHas('route.checkpoints', function ($query) use ($destination_agency, $departure_agency) {
            $query->where(function($subquery) use ($destination_agency) {
                $subquery->where('agency_id', $destination_agency->id)
                    ->whereHas('agency', function($subsubquery) {
                        $subsubquery->where('is_active', true)->whereHas('prices', function($sssq) {
                            $sssq->orderBy('start_at', 'desc');
                        });
                    });
            });
            $query->where(function($subquery) use ($departure_agency) {
                $subquery->whereHas('agency.city', function ($subsubquery) use ($departure_agency) {
                    $subsubquery->where('area_id', '!=', $departure_agency->city->area_id);
                });
            });
        })
        ->whereHas('prices', function($query) use ($date) {
            $query->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date);
        })
        ->when(($request->time_classification_id), function ($que) use ($request, $departure_agency) {
            $que->whereHas('route.checkpoints', function ($query) use ($request, $departure_agency) {
                $time_start = TimeClassification::find($request->time_classification_id)->time_start;
                $time_end = TimeClassification::find($request->time_classification_id)->time_end;
                $query->whereHas('agency.agent_departure', function($subquery) use ($time_start, $time_end, $departure_agency) {
                    $subquery->where('departure_at', '>', $time_start)->orWhere('departure_at', '<', $time_end);
                });
            });
        })
        ->get();

        foreach ($routes as $route) {
            $found = false;
            $checkpoints = $route->route->checkpoints->filter(function ($item, $key) use ($request, &$route, &$found) {
                if ($found) {
                    return false;
                }
                if ($item->agency_id == $request->agency_id) {
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
