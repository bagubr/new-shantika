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
                $query->whereHas('agency.city', function($subquery) use ($agency) {
                    $subquery->where('area_id', '=', $agency->city->area_id);
                });
            })->get()
        ]);
    }
}
