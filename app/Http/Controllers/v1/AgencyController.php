<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use App\Repositories\AgencyRepository;
use App\Http\Resources\Agency\AgencyWithCityResource;
use App\Http\Resources\Agency\AgencyWithAddressTelpResource;
use App\Repositories\UserRepository;

class AgencyController extends Controller
{
    public function index(Request $request) {
        $agency = AgencyRepository::all($request->search);
        $this->sendSuccessResponse([
            'agencies'=> AgencyWithAddressTelpResource::collection($agency)
        ]);
    }
    
    public function getWithCity(Request $request)
    {
        $agency_city = AgencyRepository::getWithCity($request);
        $user = UserRepository::findByToken($request->bearerToken());
        $this->sendSuccessResponse([
            'agencies_city'=> AgencyWithCityResource::collection($agency_city)
        ]);
    }
    
    public function getAllAgen(Request $request)
    {
        $agency = AgencyRepository::all($request->search);
        $this->sendSuccessResponse([
            'agencies'=> AgencyWithAddressTelpResource::collection($agency)
        ]);
    }
}
