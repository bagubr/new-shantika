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
        $user = User::find(99);
        $notification = new Notification([
            "title"=>'coba',
            "body"=>'coba',
            "type"=>Notification::TYPE1,
            "reference_id"=>$user->id,
            "user_id"=>$user->id
        ]);
        // dd($user->fcm_token);
        CheckOrderIsExpiredJob::dispatch($notification, $user->fcm_token,false);
        return FleetRoute::with(['fleet_detail.fleet.layout', 'route.checkpoints.agency.city', 'route.checkpoints.agency.prices'=>function($query) {
            $query->orderBy('id', 'desc');
          }, 'fleet_detail.fleet.fleetclass.prices', 'prices', 'fleet_detail', 'fleet_detail.fleet.agency_fleet'], 'route.agency_route')
                ->where('is_active', true)
                ->has('fleet_detail_without_trash')
                ->whereHas('fleet_detail', function($query) use ($time_classification_id, $fleet_class_id, $date, $departure_agency) {
                    $query->where('time_classification_id', $time_classification_id);
                    $query->whereHas('fleet', function ($subquery) use ($fleet_class_id, $date, $departure_agency)
                    {
                        $subquery->where('fleet_class_id', $fleet_class_id);
                        
                        $subquery->whereHas('agency_fleet_permanent', function ($query) use ($departure_agency)
                        {
                            $query->where('agency_id', $departure_agency->id);
                        });
                        $subquery->orDoesnthave('agency_fleet_permanent');
                        $subquery->orWhere(function ($que) use($departure_agency, $date)
                        {
                            $que->whereHas('agency_fleet', function ($query_when) use ($departure_agency, $date)
                            {
                                $query_when->where('agency_id', $departure_agency->id);
                                $query_when->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date);
                            });
                            $que->orDoesnthave('agency_fleet');
                        });
                    });
                })
                ->whereHas('route', function ($query) use ($date, $destination_agency, $departure_agency, $time_classification_id) {
                    $query->whereHas('agency_route_permanent', function ($query) use ($departure_agency)
                        {
                            $query->where('agency_id', $departure_agency->id);
                        });
                    $query->orDoesnthave('agency_route_permanent');
                    $query->orWhere(function ($que) use($departure_agency, $date)
                    {
                        $que->whereHas('agency_route', function ($query) use ($departure_agency, $date)
                            {
                                $query->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date);
                                $query->where('agency_id', $departure_agency->id);
                            });
                        $que->orDoesnthave('agency_route');
                    });
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
                ->where(function ($query) use ($time_classification_id, $departure_agency, $date, $fleet_class_id)
                {
                    $query->whereDoesntHave('time_change_route', function ($que2) use ( $time_classification_id)
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
        ->get();
    }
}
        