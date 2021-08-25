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
    public function getAvailableRoutes(ApiGetAvailableRouteRequest $request) {
        $max_date = date('Y-m-d', strtotime("+30 days"));
        if($request->date > $max_date) {
            $this->sendFailedResponse([], 'Kamu tidak bisa memesan untuk tanggal lebih dari '.$max_date);
        }
        $destination_agency = AgencyRepository::findWithCity($request->agency_arrived_id);
        $departure_agency = AgencyRepository::findWithCity($request->agency_departure_id);

        if(empty($destination_agency->is_active)) {
            return $this->sendFailedResponse([], 'Agen tujuan tidak aktif, mohon coba agen yang lain');
        }
        if(empty($departure_agency->is_active)) {
            return $this->sendFailedResponse([], 'Akun agen keberangkatan anda dinonaktifkan, mohon coba agen yang lain');
        }
        
        $routes = FleetRoute::with(['route.fleet', 'route.checkpoints.agency.city'])
            ->where('is_active', true)
            ->whereHas('fleet', function($query) use ($request) { 
                $query->where('fleet_class_id', $request->fleet_class_id);
            })
            ->whereHas('route', function($query) use ($destination_agency, $departure_agency) {
                $query->where('destination_city_id', $destination_agency->city_id)
                    ->whereHas('destination_city', function($query) use ($departure_agency) {
                        $query->where('area_id', '!=', $departure_agency->city->area_id);
                    });
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
        ]);;
    }
}
