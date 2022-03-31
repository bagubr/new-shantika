<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\v1\RouteController as BaseRouteController;
use App\Http\Requests\Api\ApiGetAvailableRouteRequest;
use App\Http\Resources\Route\AvailableRoutesResource;
use App\Models\FleetRoute;
use App\Models\TimeClassification;
use App\Repositories\AgencyRepository;
use App\Repositories\UserRepository;

class RouteController extends BaseRouteController
{
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request)
    {
        $date = $request->date;
        $user = UserRepository::findByToken($request->bearerToken());
        $departure_agency = AgencyRepository::findWithCity($user->agencies->agency_id);
        $destination_agency = AgencyRepository::findWithCity($request->agency_id);
        $time_classification_id = $request->time_classification_id;
        $fleet_class_id = $request->fleet_class_id;
        // dd($departure_agency);
        // dd($destination_agency);

        if (empty($destination_agency->is_active)) {
            return $this->sendFailedResponse([], 'Agen tujuan tidak aktif, mohon coba agen yang lain');
        }
        if (empty($departure_agency->is_active)) {
            return $this->sendFailedResponse([], 'Akun agen anda dinonaktifkan, segera lakukan setoran atau kontak admin');
        }

        $routes = FleetRoute::with(['fleet_detail.fleet.layout', 'route.checkpoints.agency.city', 'route.checkpoints.agency.prices'=>function($query) {
            $query->orderBy('id', 'desc');
          }, 'fleet_detail.fleet.fleetclass.prices', 'prices', 'fleet_detail'])
            // ->whereHas('fleet_detail.fleet.agency_fleet', function ($query) use ($departure_agency)
            // {
            //     $query->where('agency_id', $departure_agency->id);
            // })
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
                })->whereDoesntHave('time_change_route', function ($que2) use ( $time_classification_id)
                {
                    $que2->whereHas('fleet_route.fleet_detail', function ($que4) use ($time_classification_id)
                    {
                        $que4->where('time_classification_id', $time_classification_id);
                    });
                })
                ->orWhereHas('time_change_route', function ($que2) use ($date, $time_classification_id, $fleet_class_id, $departure_agency)
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
        ]);
    }
}
