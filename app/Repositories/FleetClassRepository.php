<?php

namespace App\Repositories;

use App\Models\FleetClass;

class FleetClassRepository
{
    public function all()
    {
        return FleetClass::all();
    }
}
