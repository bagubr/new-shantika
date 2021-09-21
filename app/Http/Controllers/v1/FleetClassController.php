<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiAvailableFleetClassRequest;
use App\Models\Agency;
use App\Models\FleetClass;
use App\Repositories\AgencyRepository;
use App\Repositories\TimeClassificationRepository;
use Illuminate\Http\Request;

class FleetClassController extends Controller
{
    public function index(Request $request) {
        return $this->sendSuccessResponse([
            'fleet_classes'=>FleetClass::orderBy('name')->get()
        ]);
    }

    public function available(ApiAvailableFleetClassRequest $request) {
        $agency = Agency::find($request->agency_id);
        return $this->sendSuccessResponse([
            'fleet_classes'=>FleetClass::whereHas('fleets.fleet_detail.fleet_route.route.checkpoints', function($query) use ($request, $agency) {
                $query->where('agency_id', $agency->id);
                $query->whereHas('agency.city', function($subquery) use ($agency) {
                    $subquery->where('area_id', '=', $agency->city->area_id);
                });
            })->whereHas('fleets.fleet_detail.fleet_route.prices', function($query) use ($request) {
                $query->where('start_at', '<=', $request->date)->where('end_at', '>=', $request->date);
            })->whereHas('fleets.fleet_detail', function($query) use ($request) {
                $query->where('time_classification_id', $request->time_classification_id);
            })->get()
        ]);
    }
}
