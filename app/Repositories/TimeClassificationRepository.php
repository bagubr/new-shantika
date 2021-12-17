<?php

namespace App\Repositories;
use App\Models\TimeClassification;
class TimeClassificationRepository {

    public static function findByName($name)
    {
        return TimeClassification::whereName($name)->first();
    }
}
        