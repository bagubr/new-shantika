<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index(Request $request) {
        $this->sendSuccessResponse([
            'agencies'=>Agency::where('city_id', $request->city_id)->get()
        ]);
    }
    
    public function getWithCity(Request $request)
    {
        $this->sendSuccessResponse([
            'agencies'=> AgencyRepositories::getWithCity($request)
        ]);
    }
}
