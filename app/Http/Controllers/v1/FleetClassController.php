<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiAvailableFleetClassRequest;
use App\Models\FleetClass;
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
        return $this->sendSuccessResponse([
            'fleet_classes'=>FleetClass::orderBy('name')
                ->whereHas('fleets.fleet_routes.route', function($query) use ($request) {
                    $time_start = TimeClassificationRepository::findByName($request->time)->time_start;
                    $time_end = TimeClassificationRepository::findByName($request->time)->time_end;

                    $query->where(function($subquery) use ($time_end, $time_start) {
                        $subquery->where('departure_at', '>', $time_start);
                        $subquery->orWhere('arrived_at', '<', $time_end);
                    });
                    $query->whereHas('destination_city.agent', function($subquery) use ($request) {
                        $subquery->where('id', $request->agency_id);
                    });
                })
                ->get()
        ]);
    }
}
