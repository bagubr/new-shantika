<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ApiGetAvailableRouteRequest;
use App\Http\Resources\Route\AvailableRoutesResource;
use App\Models\Route;
use App\Repositories\AgencyRepository;
use App\Repositories\TimeClassificationRepository;

class RouteController extends Controller
{
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request) {
        $max_date = date('Y-m-d', strtotime("+30 days"));
        if($request->date > $max_date) {
            $this->sendFailedResponse([], 'Kamu tidak bisa memesan untuk tanggal lebih dari '.$max_date);
        }
        $destination_agency = AgencyRepository::findWithCity($request->agency_arrived_id);
        $departure_agency = AgencyRepository::findWithCity($request->agency_departure_id);
        
        $routes = Route::with(['fleet', 'checkpoints.agency.city'])
        ->whereHas('fleet', function($query) use ($request) {
            $query->when(($request->fleet_class_id), function ($q) use ($request) { 
                $q->where('fleet_class_id', $request->fleet_class_id);
            });
        })
        ->where('destination_city_id', $destination_agency->city_id)
        ->where('departure_city_id', $departure_agency->city_id)
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
