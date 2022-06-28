<?php

namespace App\Http\Controllers;

use App\Http\Requests\FleetRoutePrice\CreateFleetRoutePriceRequest;
<<<<<<< HEAD
=======
use App\Http\Requests\FleetRoutePrice\UpdateFleetRoutePriceRequest;
>>>>>>> rilisv1
use App\Models\Area;
use App\Models\Fleet;
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
<<<<<<< HEAD
    public function index()
    {
        $fleet_route_prices = FleetRoutePrice::all();
        $fleets = Fleet::all();
        $areas = Area::all();
        return view('fleetrouteprice.index', compact('fleet_route_prices', 'fleets', 'areas'));
    }

    public function search(Request $request)
=======
    public function index(Request $request)
>>>>>>> rilisv1
    {
        $fleet_route_id = $request->fleet_route_id;
        $date_search = $request->date_search;
        $area_id = $request->area_id;

<<<<<<< HEAD
        $fleet_route_prices = FleetRoutePrice::query();

        if (!empty($fleet_route_id)) {
            $fleet_route_prices = $fleet_route_prices->whereHas('fleet_route.fleet_detail', function ($q) use ($fleet_route_id) {
                $q->where('fleet_id', $fleet_route_id);
            });
        }
        if (!empty($area_id)) {
            $fleet_route_prices = $fleet_route_prices->whereHas('fleet_route.route.checkpoints.agency.city', function ($q) use ($area_id) {
                $q->where('area_id', $area_id);
            });;
        }
        if (!empty($date_search)) {
            $fleet_route_prices = $fleet_route_prices->where('start_at', '<=', $date_search)->where('end_at', '>=', $date_search);
        }
        $fleet_route_prices     = $fleet_route_prices->get();
        $fleets = Fleet::all();
        $areas = Area::all();

        if (!$fleet_route_prices->isEmpty()) {
            session()->flash('success', 'Data Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
        return view('fleetrouteprice.index', compact('fleet_route_prices', 'fleets', 'areas', 'area_id', 'fleet_route_id', 'date_search'));
=======

        $fleet_route_prices = FleetRoutePrice::when($fleet_route_id, function ($query) use ($fleet_route_id)
        {
            $query->whereHas('fleet_route.fleet_detail', function ($q) use ($fleet_route_id) {
                $q->where('fleet_id', $fleet_route_id);
            });
        })->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('fleet_route.route.checkpoints.agency.city', function ($q) use ($area_id) {
                $q->where('area_id', $area_id);
            });
        })->when($date_search, function ($query) use ($date_search)
        {
            $query->where('start_at', '<=', $date_search)->where('end_at', '>=', $date_search);
        })->get();
        $fleets = Fleet::all();
        $areas = Area::all();
        $area = Area::find($area_id);

        return view('fleetrouteprice.index', compact('fleet_route_prices', 'fleets', 'areas', 'area_id', 'fleet_route_id', 'date_search', 'area'));
>>>>>>> rilisv1
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function create()
    {
        $fleet_routes = FleetRoute::all();
        return view('fleetrouteprice.create', compact('fleet_routes'));
=======
    public function create(Request $request)
    {
        $area_id = $request->area_id;
        $area = Area::find($area_id);
        $fleet_routes = FleetRoute::whereHas('route.checkpoints.agency.city', function ($q) use ($area_id) {
            $q->where('area_id', $area_id);
        })->get();
        return view('fleetrouteprice.create', compact('fleet_routes', 'area'));
>>>>>>> rilisv1
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
                'deviation_price' => $request['deviation_price'],
                'note' => $request['note']
            ]);
        }
        session()->flash('success', 'Harga Rute Armada Berhasil Ditambahkan');
<<<<<<< HEAD
        return redirect(route('fleet_route_prices.index'));
=======
        return redirect(route('fleet_route_prices.index', ['area_id' => $request->area_id]));
>>>>>>> rilisv1
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
<<<<<<< HEAD
        $fleet_routes = FleetRoute::all();
        return view('fleetrouteprice.create', compact('fleet_route_price', 'fleet_routes'));
=======
        $area_id = $fleet_route_price->fleet_route->route->checkpoints[0]->agency->city->area_id;
        $area = Area::find($area_id);
        $fleet_routes = FleetRoute::all();
        return view('fleetrouteprice.create', compact('fleet_route_price', 'fleet_routes', 'area', 'area_id'));
>>>>>>> rilisv1
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function update(CreateFleetRoutePriceRequest $request, FleetRoutePrice $fleet_route_price)
    {
        $data = $request->all();
        $fleet_route_price->update($data);
        session()->flash('success', 'Harga Rute Armada Berhasil Diubah');
        return redirect(route('fleet_route_prices.index'));
=======
    public function update(UpdateFleetRoutePriceRequest $request, FleetRoutePrice $fleet_route_price)
    {
        $data = $request->all();
        $fleet_route_price->update($data);
        $fleet_route_price->refresh();
        session()->flash('success', 'Harga Rute Armada Berhasil Diubah');
        return redirect(route('fleet_route_prices.index', ['area_id' => $fleet_route_price->fleet_route->route->checkpoints[0]->agency->city->area_id]));
>>>>>>> rilisv1
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
