<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class SketchController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date??null;
        $area_id = $request->area_id??null;
        $orders = Order::whereIn('status', Order::STATUS_BOUGHT)
        ->with('distribution')
        ->when(($request->date), function ($query) use ($request)
        {
            $query->whereDate('reserve_at', $request->date);
        })->get()->groupBy('route_id');
        
        $data = [
            'sketchs' => $orders
        ];
        return view('sketch.index', compact('data', 'date', 'area_id'));
    }
}
