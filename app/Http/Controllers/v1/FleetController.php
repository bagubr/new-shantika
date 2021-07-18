<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\FleetRepository;
class FleetController extends Controller
{
    public function index(Request $request)
    {
        $this->sendSuccessResponse([
            'data'=>FleetRepository::allWithSearch($request->search)
        ]);
    }
}
