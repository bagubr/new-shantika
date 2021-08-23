<?php

namespace App\Http\Controllers;

use App\Http\Requests\FleetRoute\CreateFleetRouteRequest;
use App\Http\Requests\Routes\CreateRoutesRequest;
use App\Http\Requests\Routes\UpdateRouteRequest;
use App\Models\Area;
use App\Models\Checkpoint;
use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetRoute;
use App\Models\Route;
use App\Repositories\AgencyRepository;
use App\Repositories\RoutesRepository;
use Illuminate\Support\Facades\Route as FacadesRoute;

class RoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routes = RoutesRepository::all();
        return view('routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $name = FacadesRoute::currentRouteName();
        $areas = Area::all();
        $cities = City::all();
        $fleets = Fleet::all();
        return view('routes.create', compact('cities', 'areas', 'fleets', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoutesRequest $request)
    {
        $data = $request->all();
        $route = Route::create($data);
        foreach ($request->fleet_id as $key => $value) {
            FleetRoute::create([
                'route_id' => $route->id,
                'fleet_id' => $value,
                'price' => $request->price[$key]
            ]);
        }
        $name = '~' . $route->departure_city()->first()->name . '~' . $route->destination_city()->first()->name . '~';
        $route->update([
            'name' => $name,
        ]);

        session()->flash('success', 'Route Berhasil Ditambahkan');
        return redirect(route('routes.index'));

        // $data = $request->all();
        // $route = Route::create($data);

        // $agencies = $request['agency_id'];
        // $arrived_at = $request['arrived_at1'];

        // $i = 0;
        // $checkpoints = '';
        // foreach ($agencies as $key => $agency) {
        //     $checkpoint = Checkpoint::create([
        //         'route_id' => $route->id,
        //         'arrived_at' => $arrived_at[$key],
        //         'agency_id' => $agency,
        //         'order' => $i++
        //     ]);
        //     $checkpoints .= '~' . $checkpoint->agency()->first()->name . '~';
        // }
        // $route->update([
        //     'name' => $checkpoints,
        // ]);
        // session()->flash('success', 'Route Berhasil Ditambahkan');
        // return redirect(route('routes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        $checkpoints = Checkpoint::where('route_id', $route->id)->orderBy('order')->get();
        $checkpoint_id = Checkpoint::where('route_id', $route->id)->get(['agency_id'])->toArray();
        $agencies = AgencyRepository::all();
        $fleets = Fleet::all();
        $route_fleets = FleetRoute::where('route_id', $route->id)->get();
        return view('routes.show', compact('route', 'agencies', 'checkpoints', 'fleets', 'route_fleets'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
        $areas = Area::all();
        $cities = City::all();
        $fleets = Fleet::all();
        return view('routes.create', compact('areas', 'cities', 'route', 'fleets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRouteRequest $request, Route $route)
    {
        $data = $request->all();
        $route->update($data);
        $name = '~' . $route->departure_city()->first()->name . '~' . $route->destination_city()->first()->name . '~';
        $route->update([
            'name' => $name,
        ]);
        session()->flash('success', 'Route Berhasil Diperbarui');
        return redirect(route('routes.index'));
    }
    public function store_fleet(CreateFleetRouteRequest $request)
    {
        $data = $request->all();
        FleetRoute::create($data);
        session()->flash('success', 'Route Armada Berhasil Ditambahkan');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Route $route)
    {
        $route->checkpoints()->delete();
        $route->delete();
        session()->flash('success', 'Route Berhasil Dihapus');
        return redirect(route('routes.index'));
    }
}
