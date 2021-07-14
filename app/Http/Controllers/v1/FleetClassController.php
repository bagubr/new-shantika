<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\FleetClass;
use Illuminate\Http\Request;

class FleetClassController extends Controller
{
    public function index(Request $request) {
        return $this->sendSuccessResponse([
            'fleet_classes'=>FleetClass::orderBy('name')->get()
        ]);
    }
}
