<?php

namespace App\Http\Controllers;

use App\Models\FleetRoute;
use App\Models\Order;
use App\Models\TimeChangeRoute;
use App\Models\TimeClassification;
use App\Services\TimeChangeService;
use Illuminate\Http\Request;

class TimeChangeRouteController extends Controller
{
    public function index()
    {
        $time_change_routes = TimeChangeRoute::all();
        return view('time_change.index', compact('time_change_routes'));
    }

    public function create()
    {
        $fleet_routes = FleetRoute::all();
        $time_classifications = TimeClassification::all();
        return view('time_change.create', compact('fleet_routes', 'time_classifications'));
    }
    
    public function store(Request $request)
    {
        // $user = Order::with('user')->whereHas('user', function ($query)
        // {
        //     $query->whereNotNull('fcm_token');
        // })->whereDate('reserve_at', $request->date)->where('fleet_route_id' ,$request->fleet_route_id)->get();
        // dd($user);
        // dd($request->time_classification_id);
        TimeChangeService::create($request->all());
        session()->flash('success', 'Waktu Berhasil Ditambahkan');
        return redirect(route('time_change_route.index'));
    }

    public function edit(TimeChangeRoute $time_change_route)
    {
        $fleet_routes = FleetRoute::all();
        $time_classifications = TimeClassification::all();
        return view('time_change.create', compact('time_change_route', 'fleet_routes', 'time_classifications'));
    }

    public function update(Request $request, TimeChangeRoute $time_change_route)
    {
        $data = $request->all();
        $time_change_route->update($data);

        session()->flash('success', 'Waktu Berhasil Dirubah');
        return redirect(route('time_change.index'));
    }

    public function destroy(TimeChangeRoute $time_change_route)
    {
        $time_change_route->delete();
        session()->flash('success', 'Berhasil Dihapus');
        return redirect(route('time_change_route.index'));
    }

}
