<?php

namespace App\Repositories;

use App\Models\AgencyDepartureTime;

class AgencyDepartureTimeRepository {
    public static function findByAgencyAndTimeClassification($agency_id, $time_classification_id) {
        return AgencyDepartureTime::with('agency')->where('agency_id', $agency_id)->get()->where('time_classification_id', $time_classification_id)->first();
    }
}
        