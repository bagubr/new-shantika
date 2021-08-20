<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class SketchController extends Controller
{
    public function index(Request $request)
    {
        return view('sketch.index_1');
    }

    public function getDeparturingOrders(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $area_id = $request->area_id;
        $orders = Order::whereIn('status', Order::STATUS_BOUGHT)
            ->with('fleet_route.fleet.fleetclass', 'fleet_route.route')
            ->with('fleet_route.fleet.layout')
            ->withCount(['order_detail'=>function($query) {
                $query->whereHas('order', function($subquery) {
                    $subquery->whereRaw('fleet_route_id = orders.fleet_route_id');
                });
            }])
            ->when($date, function ($query) use ($date) {
                $query->whereDate('reserve_at', $date);
            })
            ->when($area_id, function($query) use ($area_id) {
                $query->whereHas('fleet_route.route.departure_city', function($subquery) use ($area_id) {
                    $subquery->where('area_id', $area_id);
                });
            })
            ->distinct('fleet_route_id')
            ->get();
        
        return response([
            'orders'=>$orders
        ]);
    }
}
