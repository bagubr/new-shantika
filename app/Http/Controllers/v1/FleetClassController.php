<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiAvailableFleetClassRequest;
use App\Models\Agency;
use App\Models\FleetClass;
use App\Models\TimeClassification;
use App\Repositories\AgencyRepository;
use App\Repositories\TimeClassificationRepository;
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
        $agency = Agency::find($request->agency_id);

        $pdo = DB::getPdo();
        $query=$pdo->prepare("
            select fleet_classes.id, fleet_classes.name, fleet_classes.price_food from fleet_classes
            where exists (
                select * from fleet_details
                left join fleets on fleet_details.fleet_id = fleets.id
                where fleets.fleet_class_id = fleet_classes.id
                and fleet_details.time_classification_id = ?
                and fleet_details.deleted_at is null
            )
            and exists (
                select * from fleet_routes
                left join fleet_details on fleet_details.id = fleet_routes.fleet_detail_id
                left join fleets on fleets.id = fleet_details.fleet_id
                where fleets.fleet_class_id = fleet_classes.id
                and exists (
                    select * from fleet_route_prices where fleet_route_prices.fleet_route_id = fleet_routes.id
                    and fleet_route_prices.start_at <= ?
                    and fleet_route_prices.end_at >= ?
                )
                and exists (
                    select * from checkpoints
                    left join routes on  routes.id = checkpoints.route_id
                    where fleet_routes.route_id = routes.id
                    and checkpoints.agency_id = ?
                    and exists (
                        select * from cities
                        left join agencies on agencies.city_id = cities.id
                        where checkpoints.agency_id = agencies.id
                        and cities.area_id = ?
                        and cities.deleted_at is null
                    )
                )
                and fleet_routes.deleted_at is null
            )
            and fleet_classes.deleted_at is null
        ");
        $query->execute([$request->time_classification_id, $request->date, $request->date, $agency->id, $agency->city->area_id]);

        $result = [];
        while($row=$query->fetch(\PDO::FETCH_OBJ)) {
            $result[] = $row;
        }

        return $this->sendSuccessResponse([
            'fleet_classes'=>$result
        ]);
    }
}
