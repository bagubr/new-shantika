<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Layout\LayoutResource;
use App\Models\Route;
use App\Repositories\LayoutRepository;
use App\Services\LayoutService;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function index(Request $request)
    {
        $route = Route::find($request->route_id);
        $layout = LayoutRepository::findByRoute($route);
        $layout = LayoutService::getAvailibilityChairs($layout, $route, $request->date);
        return new LayoutResource($layout);
        
        $this->sendSuccessResponse([
            'data'=> $layout
        ]);
    }
}
