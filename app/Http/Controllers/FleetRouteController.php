<?php

namespace App\Http\Controllers;

use App\Http\Requests\FleetRoute\CreateFleetRouteRequest;
use App\Http\Requests\FleetRoute\UpdateFleetRouteRequest;
use App\Models\Agency;
use App\Models\FleetRoute;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FleetRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fleet_routes = FleetRoute::all();
        $statuses = Agency::status();
        return view('fleetroute.index', compact('fleet_routes', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFleetRouteRequest $request)
    {
        $data = $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FleetRoute $fleet_route)
    {
        $orders = Order::where('fleet_route_id', $fleet_route->id)->get();
        $statuses = Agency::status();
        return view('fleetroute.show', compact('fleet_route', 'statuses', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FleetRoute $fleet_route)
    {
        $statuses = Agency::status();
        return view('fleetroute.edit', compact('fleet_route', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFleetRouteRequest $request, FleetRoute $fleet_route)
    {
        $data = $request->all();
        $fleet_route->update($data);
        session()->flash('success', 'Rute Armada Berhasil Di Ubah');
        return redirect()->back();
    }
    public function update_status(Request $request, FleetRoute $fleet_route)
    {
        $fleet_route->update([
            'is_active' => $request->is_active,
        ]);
        session()->flash('success', 'Status Rute Armada Berhasil Diubah');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FleetRoute $fleetRoute)
    {
        $fleetRoute->delete();
        session()->flash('success', 'Armada Rute berhasil dihapus');
        return redirect()->back();
    }
}
