<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;
use App\Repositories\AgencyRepository;
use App\Http\Resources\Agency\AgencyWithAddressTelpResource;

class InformationController extends Controller
{
    public function index(Request $request) {
        $agency = AgencyRepository::all($request->search);
        $this->sendSuccessResponse([
            'agencies'=> AgencyWithAddressTelpResource::collection($agency)
        ]);
    }
}
