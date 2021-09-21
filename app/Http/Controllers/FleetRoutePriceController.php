<?php

namespace App\Http\Controllers;

use App\Http\Requests\FleetRoutePrice\CreateFleetRoutePriceRequest;
use App\Models\FleetDetail;
use App\Models\FleetRoute;
use App\Models\FleetRoutePrice;
use Illuminate\Http\Request;

class FleetRoutePriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fleet_route_prices = FleetRoutePrice::all();
        $fleet_details = FleetDetail::has('fleet_route')->get();

        return view('fleetrouteprice.index', compact('fleet_route_prices', 'fleet_details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fleet_routes = FleetRoute::all();
        return view('fleetrouteprice.create', compact('fleet_routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFleetRoutePriceRequest $request)
    {
        foreach ($request->fleet_route_id as $key => $value) {
            FleetRoutePrice::updateOrCreate([
                'fleet_route_id' => $value,
                'start_at' => $request['start_at'],
            ], [
                'end_at' => $request['end_at'],
                'color' => $request['color'],
                'price' => $request['price'],
                'note' => $request['note']
            ]);
        }
        session()->flash('success', 'Harga Rute Armada Berhasil Ditambahkan');
        return redirect(route('fleet_route_prices.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FleetRoutePrice $fleet_route_price)
    {
        $fleet_routes = FleetRoute::all();
        return view('fleetrouteprice.create', compact('fleet_route_price', 'fleet_routes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateFleetRoutePriceRequest $request, FleetRoutePrice $fleet_route_price)
    {
        $data = $request->all();
        $fleet_route_price->update($data);
        session()->flash('success', 'Harga Rute Armada Berhasil Diubah');
        return redirect(route('fleet_route_prices.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FleetRoutePrice $fleet_route_price)
    {
        $fleet_route_price->delete();
        session()->flash('success', 'Harga Rute Armada Berhasil Dihapus');
        return back();
    }
}
