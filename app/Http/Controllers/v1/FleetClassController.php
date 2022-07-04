<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiAvailableFleetClassRequest;
use App\Models\Agency;
use App\Models\FleetClass;
use App\Models\TimeClassification;
use App\Repositories\AgencyRepository;
use App\Repositories\TimeClassificationRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FleetClassController extends Controller
{
    public function index(Request $request) {
        return $this->sendSuccessResponse([
            'fleet_classes'=>FleetClass::withCount('fleets')->orderBy('name')->get()
        ]);
    }

    public function available(ApiAvailableFleetClassRequest $request) {
        $time_classification_id = $request->time_classification_id;
        $date = $request->date;
        $agency_id = $request->agency_id;
        $agency = Agency::find($agency_id);
        $fleet_class = FleetClass::has('fleets.fleet_detail.fleet_route.prices')->select('id', 'name', 'price_food')
        ->whereHas('fleets.fleet_detail', function ($query) use ($time_classification_id, $date, $agency_id, $agency)
        {
            $query->where('time_classification_id', $time_classification_id);
            $query->whereHas('fleet_route', function ($query) use ($date, $agency_id, $agency, $time_classification_id)
            {
                $query->where('is_active', true);
                $query->whereHas('prices', function ($query) use ($date)
                {
                    $query->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date);
                });
                $query->whereHas('route.checkpoints', function ($query) use ($agency_id, $agency, $time_classification_id)
                {
                    $time_start = TimeClassification::find($time_classification_id)->time_start;
                    $time_end = TimeClassification::find($time_classification_id)->time_end;
                    $query->whereHas('agency.agent_departure', function($subquery) use ($time_start, $time_end) {
                        $subquery->where('departure_at', '>', $time_start)->orWhere('departure_at', '<', $time_end);
                    });
                    $query->where('agency_id', $agency_id);
                    $query->whereHas('agency', function ($query) use ($agency)
                    {
                        $query->where('is_active', true);
                        $query->whereHas('city.area', function ($query) use ($agency)
                        {
                            $query->where('id', $agency->city->area_id);
                        });
                    });
                });
            });
        })
        ->get()->makeHidden(['price_fleet_class1', 'price_fleet_class2']);

        return $this->sendSuccessResponse([
            'fleet_classes'=>$fleet_class
        ]);
    }
}
