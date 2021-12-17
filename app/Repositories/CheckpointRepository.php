<?php

namespace App\Repositories;

use App\Models\Checkpoint;

class CheckpointRepository {
    public static function findByRouteAndAgency($route_id, $agency_id) {
        return Checkpoint::where('route_id', $route_id)->where('agency_id', $agency_id)->first();
    }
}
        