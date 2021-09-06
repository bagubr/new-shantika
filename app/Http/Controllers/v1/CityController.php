<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Repositories\AgencyRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        $agency = AgencyRepository::findWithCity($user->agencies?->agency_id);
        
        $this->sendSuccessResponse([
            'cities'=>City::orderBy('name')->when($agency, function($query) use ($agency) {
                $query->where('area_id',"!=",$agency->city->area_id);
            })->get()
        ]);
    }
}
