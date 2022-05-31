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
use App\Repositories\FleetRouteRepositories;
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
        $routes = FleetRouteRepositories::search_fleet($date, $departure_agency, $destination_agency, $time_classification_id, $fleet_class_id);

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
