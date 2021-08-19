<?php

namespace App\Http\Controllers;

use App\Http\Requests\Routes\CreateRoutesRequest;
use App\Http\Requests\Routes\UpdateRouteRequest;
use App\Models\Agency;
use App\Models\Area;
use App\Models\Checkpoint;
use App\Models\City;
use App\Models\Route;
use App\Repositories\AgencyRepository;
use App\Repositories\FleetRepository;
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
        $areas = Area::all();
        $cities = City::all();
        return view('routes.create', compact('cities', 'areas'));
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
        return view('routes.show', compact('route', 'agencies', 'checkpoints'));
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
        return view('routes.create', compact('areas', 'cities', 'route'));
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
