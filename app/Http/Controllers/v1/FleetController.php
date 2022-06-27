<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\FleetRepository;
use App\Http\Resources\Fleet\FleetDetailResource;
use App\Models\Fleet;
class FleetController extends Controller
{
    public function index(Request $request)
    {
        
        $this->sendSuccessResponse([
            'fleet_detail'=>FleetRepository::allWithRoute($request->search, $request->fleet_class_id)
        ]);
    }

    public function fleet_list(Request $request)
    {
        $fleet = FleetRepository::allWithRoute($request->search, $request->fleet_class_id);
        $fleet_detail = self::show($fleet[0]->id);
        $this->sendSuccessResponse([
            'fleet_detail'=>$fleet_detail
        ]);
    }
    
    public function show($id)
    {
        $this->sendSuccessResponse([
            'fleet_detail'=> new FleetDetailResource(Fleet::find($id)),
        ]);
    }
}
