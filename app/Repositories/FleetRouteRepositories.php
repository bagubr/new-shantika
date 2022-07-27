<?php

namespace App\Repositories;

use App\Jobs\CheckOrderIsExpiredJob;
use App\Models\FleetRoute;
use App\Models\Notification;
use App\Models\TimeClassification;
use App\Models\User;

class FleetRouteRepositories {
    public static function search_fleet($date, $departure_agency, $destination_agency, $time_classification_id, $fleet_class_id)
    {
        $routes = FleetRoute::with(['fleet_detail.fleet.layout', 'time_change_route', 'route.checkpoints.agency.city', 'route.checkpoints.agency.prices'=>function($query) {
            $query->orderBy('id', 'desc');
          }, 'fleet_detail.fleet.fleetclass.prices', 'prices', 'fleet_detail', 'fleet_detail.fleet.agency_fleet'], 'route.agency_route')
                ->where('is_active', true)
                ->has('fleet_detail_without_trash')
                ->whereHas('route', function ($query) use ($date, $destination_agency, $departure_agency, $time_classification_id) {
                    $query->whereHas('checkpoints', function ($query) use ($date, $destination_agency, $departure_agency, $time_classification_id) {
                        $time_start = TimeClassification::find($time_classification_id)->time_start;
                        $time_end = TimeClassification::find($time_classification_id)->time_end;
                        $query->whereHas('agency.agent_departure', function($subquery) use ($time_start, $time_end) {
                            $subquery->where('departure_at', '>', $time_start)->orWhere('departure_at', '<', $time_end);
                        });
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
                                $subsubquery->whereHas('city', function ($subsubquery) use ($departure_agency) {
                                    $subsubquery->where('area_id', '!=', $departure_agency->city->area_id);
                                });
                            });
                        });
                    });
                })
                ->whereHas('prices', function($query) use ($date) {
                    $query->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date);
                })
                ->where(function ($query) use ($departure_agency)
                {
                    $query->whereHas('route.checkpoints.agency.city', function ($subsubquery) use ($departure_agency) {
                        $subsubquery->where('area_id', '!=', $departure_agency->city->area_id);
                    });
                })
                ->where(function ($query) use ($time_classification_id, $date)
                {
                    $query->whereHas('fleet_detail', function ($query) use ($time_classification_id)
                    {
                        $query->where(function ($query) use ($time_classification_id)
                        {
                            $query->where('time_classification_id', $time_classification_id);
                        });
                    });
                    $query->orWhere(function ($query) use ($time_classification_id, $date)
                    {
                        $query->whereHas('time_change_route', function ($query) use ($time_classification_id, $date)
                        {
                            $query->where('time_classification_id', $time_classification_id);
                            $query->whereDate('date', $date);
                        });
                    });
                })
                ->where(function ($query) use ($departure_agency, $date, $fleet_class_id)
                {
                    $query->whereHas('fleet_detail.fleet', function ($query) use ($date, $departure_agency, $fleet_class_id) {
                        $query->where('fleet_class_id', $fleet_class_id);
                        $query->where(function ($query) use($departure_agency, $date) {
                            $query->whereHas('agency_fleet_permanent', function ($query) use ($departure_agency)
                            {
                                $query->where('agency_id', $departure_agency->id);
                                $query->whereNull('start_at');
                                $query->whereNull('end_at');
                            });
                            $query->orWhereHas('agency_fleet_permanent', function ($query) use ($date)
                            {
                                $query->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date);
                                $query->whereNotNull('start_at');
                                $query->whereNotNull('end_at');
                            });
                            $query->orDoesnthave('agency_fleet_permanent');
                        });
                    });
                })
                ->where(function ($query) use ($departure_agency, $date)
                {
                    $query->whereHas('route', function ($query) use ($date, $departure_agency) {
                        $query->where(function ($query) use($departure_agency, $date) {
                            $query->whereHas('agency_route_permanent', function ($query) use ($departure_agency, $date)
                            {
                                $query->where('agency_id', $departure_agency->id);
                                $query->whereNull('start_at');
                                $query->whereNull('end_at');
                            });
                            $query->orWhereHas('agency_route_permanent', function ($query) use ($departure_agency, $date)
                            {
                                $query->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date);
                                $query->whereNotNull('start_at');
                                $query->whereNotNull('end_at');
                            });
                            $query->orDoesnthave('agency_route_permanent');
                        });
                    });
                })
        ->get();
        foreach($routes as $key => $route){
            if($route->time_change_route && strtotime($route->time_change_route->date) == strtotime($date)){
                if($route->time_change_route->time_classification_id != $time_classification_id){
                    unset($routes[$key]);
                }
            }
        };
        return $routes;
    }
}