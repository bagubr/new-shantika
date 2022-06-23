<?php
namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Layout\LayoutResource;
use App\Models\FleetRoute;
use App\Repositories\LayoutRepository;
use App\Services\LayoutService;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function index(Request $request)
    {
        $fleet_route = FleetRoute::find($request->fleet_route_id);
        $layout = LayoutRepository::findByFleetRoute($fleet_route);
        $layout = LayoutService::getAvailibilityChairs($layout, $fleet_route, $request->date);
        
        $this->sendSuccessResponse([
            'data'=> new LayoutResource($layout)
        ]);
    }
}
