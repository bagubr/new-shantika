<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use App\Repositories\AgencyRepository;
use App\Http\Resources\Agency\AgencyWithCityResource;
use App\Http\Resources\Agency\AgencyWithAddressTelpResource;

class AgencyController extends Controller
{
    public function index(Request $request) {
        $this->sendSuccessResponse([
            'agencies'=>Agency::where('city_id', $request->city_id)->get()
        ]);
    }
    
    public function getWithCity(Request $request)
    {
        $agency_city = AgencyRepository::getWithCity($request);
        $this->sendSuccessResponse([
            'agencies_city'=> AgencyWithCityResource::collection($agency_city)
        ]);
    }
    
    public function getAllAgen(Request $request)
    {
        $agency_city = AgencyRepository::getWithCity($request);
        $this->sendSuccessResponse([
            'agencies'=> AgencyWithCityResource::collection($agency_city)
        ]);
        // $agency = AgencyRepository::all($request->search);
        // $this->sendSuccessResponse([
        //     'agencies'=> AgencyWithAddressTelpResource::collection($agency)
        // ]);
    }
}
