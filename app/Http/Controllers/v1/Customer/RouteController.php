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
<<<<<<< HEAD
=======
use App\Repositories\FleetRouteRepositories;
>>>>>>> rilisv1
use App\Repositories\TimeClassificationRepository;
use Illuminate\Database\Eloquent\Builder;

class RouteController extends Controller
{
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request)
    {
        $date = $request->date;
        $destination_agency = AgencyRepository::findWithCity($request->agency_arrived_id);
        $departure_agency = AgencyRepository::findWithCity($request->agency_departure_id);
        $time_classification_id = $request->time_classification_id;
        $fleet_class_id = $request->fleet_class_id;

        if (empty($destination_agency->is_active)) {
            return $this->sendFailedResponse([], 'Agen tujuan tidak aktif, mohon coba agen yang lain');
        }
        if (empty($departure_agency->is_active)) {
            return $this->sendFailedResponse([], 'Akun agen keberangkatan anda dinonaktifkan, mohon coba agen yang lain');
        }
<<<<<<< HEAD

        $routes = FleetRoute::with(['fleet_detail.fleet.layout', 'route.checkpoints.agency.city', 'route.checkpoints.agency.prices'=>function($query) {
            $query->orderBy('id', 'desc');
          }, 'fleet_detail.fleet.fleetclass.prices', 'prices', 'fleet_detail', 'fleet_detail.fleet.agency_fleet'])
                ->where('is_active', true)
                ->whereHas('fleet_detail', function($query) use ($time_classification_id, $fleet_class_id) {
                    $query->where('time_classification_id', $time_classification_id);
                    $query->whereHas('fleet', function ($subquery) use ($fleet_class_id)
                    {
                        $subquery->where('fleet_class_id', $fleet_class_id);
                    });
                })->whereHas('route.checkpoints', function ($query) use ($date, $destination_agency, $departure_agency) {
                    $query->where(function($subquery) use ($date, $destination_agency, $departure_agency) {
                        $subquery->where('agency_id', $destination_agency->id)
                            ->whereHas('agency', function($subsubquery) use ($date, $departure_agency) {
                                $subsubquery->where('is_active', true)
                                ->when($departure_agency->city->area_id == 1, function ($subsubsubquery) use ($date)
                                {
                                    $subsubsubquery->whereHas('prices', function($subsubsubsubquery) use ($date) {
                                        $subsubsubsubquery->where('start_at', '<=', $date);
                                    });
                                });
                            });
                    });
                    $query->where(function($subquery) use ($departure_agency) {
                        $subquery->whereHas('agency.city', function ($subsubquery) use ($departure_agency) {
                            $subsubquery->where('area_id', '!=', $departure_agency->city->area_id);
                        });
                    });
                })->whereHas('prices', function($query) use ($date) {
                    $query->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date);
                })->when(($time_classification_id), function ($que) use ($date, $time_classification_id, $fleet_class_id, $departure_agency) {
                    $que->whereHas('route.checkpoints', function ($query) use ($time_classification_id) {
                        $time_start = TimeClassification::find($time_classification_id)->time_start;
                        $time_end = TimeClassification::find($time_classification_id)->time_end;
                        $query->whereHas('agency.agent_departure', function($subquery) use ($time_start, $time_end) {
                            $subquery->where('departure_at', '>', $time_start)->orWhere('departure_at', '<', $time_end);
                        });
                    });
                })
                ->where(function ($query) use ($time_classification_id, $departure_agency, $date, $fleet_class_id)
                {
                    $query->whereDoesntHave('time_change_route', function ($que2) use ( $time_classification_id, $departure_agency)
                    {
                        $que2->whereHas('fleet_route.fleet_detail', function ($que4) use ($time_classification_id)
                        {
                            $que4->where('time_classification_id', $time_classification_id);
                        });
                    })->orWhereHas('time_change_route', function ($que2) use ($date, $time_classification_id, $fleet_class_id, $departure_agency)
                    {
                        $que2->where(function ($que3) use ($date, $time_classification_id, $fleet_class_id, $departure_agency)
                        {
                            $que3->whereDate('date', $date);
                            $que3->where('time_classification_id', $time_classification_id);
                            $que3->whereHas('fleet_route.fleet_detail.fleet', function ($que4) use ( $fleet_class_id)
                            {
                                $que4->where('fleet_class_id', $fleet_class_id);
                            });
                            $que3->whereHas('fleet_route.route.checkpoints.agency.city', function ($subsubquery) use ($departure_agency) {
                                $subsubquery->where('area_id', '!=', $departure_agency->city->area_id);
                            });
                        })
                        ->orWhereDate('date', '!=', $date);
                    });
                })
                ->where(function ($que) use($departure_agency, $date)
                {
                    $que->whereHas('fleet_detail.fleet.agency_fleet', function ($query) use ($departure_agency, $date)
                        {
                            $query->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date);
                            $query->where('agency_id', $departure_agency->id);
                        });
                    $que->orDoesnthave('fleet_detail.fleet.agency_fleet');
                })
                ->where(function ($que) use($departure_agency)
                {
                    $que->whereHas('fleet_detail.fleet.agency_fleet_permanent', function ($query) use ($departure_agency)
                        {
                            $query->where('agency_id', $departure_agency->id);
                        });
                    $que->orDoesnthave('fleet_detail.fleet.agency_fleet_permanent');
                })
        ->get();
=======
        $routes = FleetRouteRepositories::search_fleet($date, $departure_agency, $destination_agency, $time_classification_id, $fleet_class_id);
>>>>>>> rilisv1

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
