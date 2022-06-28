<?php

namespace App\Http\Controllers;

use App\Http\Requests\FleetRoute\CreateFleetRouteRequest;
use App\Http\Requests\Routes\CreateRoutesRequest;
use App\Http\Requests\Routes\UpdateRouteRequest;
use App\Models\Agency;
use App\Models\Area;
use App\Models\Checkpoint;
use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\FleetRoute;
use App\Models\Route;
<<<<<<< HEAD
use App\Repositories\RoutesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route as FacadesRoute;
=======
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
>>>>>>> rilisv1

class RoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function index()
    {
        $routes = RoutesRepository::all();
        $areas = Area::all();
        return view('routes.index', compact('routes', 'areas'));
    }
    public function search(Request $request)
    {
        $area_id = $request->area_id;
        $areas = Area::get();
        $routes = Route::query();

        if (!empty($area_id)) {
            $routes = $routes->whereHas('checkpoints.agency', function ($q) use ($area_id) {
                $q->whereHas('city', function ($sq) use ($area_id) {
                    $sq->where('area_id', '!=', $area_id);
                });
            });
        }
        $test     = $request->flash();
        $routes   = $routes->get();
        if (!$routes->isEmpty()) {
            session()->flash('success', 'Data Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
        return view('routes.index', compact('routes', 'areas', 'test'));
    }

=======
    public function index(Request $request)
    {
        $area_id = Auth::user()->area_id??$request->area_id;
        
        $areas = Area::all();
        
        $routes = Route::when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('checkpoints.agency', function ($q) use ($area_id) {
                $q->whereHas('city', function ($sq) use ($area_id) {
                    $sq->where('area_id', $area_id);
                });
            });
        })->orderBy('id', 'desc')->get();
        
        return view('routes.index', compact('routes', 'areas', 'area_id'));
    }
    
>>>>>>> rilisv1
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function create()
    {
        $name = FacadesRoute::currentRouteName();
        $areas = Area::all();
        $cities = City::all();
        $fleets = FleetDetail::all();
        return view('routes.create', compact('cities', 'areas', 'fleets', 'name'));
    }

=======
    public function create(Request $request)
    {
        $area_id = $request->area_id;
        $areas = Area::all();
        $agencies = Agency::when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        })
        ->get();
        return view('routes.create', compact('areas', 'agencies', 'area_id'));
    }
    
>>>>>>> rilisv1
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoutesRequest $request)
    {
<<<<<<< HEAD
        $data = $request->all();
        $route = Route::create($data);

        $agencies = $request['agency_id'];

        $i = 1;
        $checkpoints = '';
        // foreach ($request->fleet_detail_id as $key => $value) {
        //     FleetRoute::create([
        //         'route_id' => $route->id,
        //         'fleet_detail_id' => $value,
        //         'price' => $request->price[$key]
        //     ]);
        // }
        foreach ($agencies as $key => $agency) {
            $checkpoint = Checkpoint::create([
                'route_id' => $route->id,
                'agency_id' => $agency,
                'order' => $i++
            ]);
            $checkpoints .= '~' . $checkpoint->agency()->first()->name . '~';
        }
        $route->update([
            'name' => $checkpoints,
        ]);
        session()->flash('success', 'Route Berhasil Ditambahkan');
        return redirect(route('routes.show', $route->id));
    }

=======
        $areas = Area::all();
        $area_id = $request->area_id;
        if($request->route_id){
            if($request->agency_id){
                $route = Route::find($request->route_id);
                $route->name .= '~' . Agency::find($request->agency_id)->name . '~';
                $route->update([
                    'name' => $route->name
                ]);
            }
        }else{
            $data['name'] = '~' . Agency::find($request->agency_id)->name . '~';
            $route = Route::create($data);
        }
        $checkpoints = Checkpoint::create([
            'route_id' => $route->id,
            'agency_id' => $request->agency_id,
            'order' => $request->order
        ]);
        $request->offsetUnset('agency_id');
        $agencies = Agency::when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        })
        ->whereNotIn('id', $route->checkpoints()->pluck('agency_id'))
        ->get();
        session()->flash('success', 'Route Berhasil Ditambahkan');
        return view('routes.create', compact('areas', 'agencies', 'area_id', 'route'));
    }
    
>>>>>>> rilisv1
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        $checkpoints = Checkpoint::where('route_id', $route->id)->orderBy('order')->get();
<<<<<<< HEAD
        $checkpoint_id = Checkpoint::where('route_id', $route->id)->get(['agency_id'])->toArray();
=======
        return view('routes.show', compact('route', 'checkpoints'));
    }

    public function duplicate(Route $route, Request $request)
    {
        
>>>>>>> rilisv1
        if ($route->checkpoints->count() == 0) {
            $agencies = Agency::all();
        } else {
            $agencies = Agency::whereHas('city', function ($q) use ($route) {
                $q->where('area_id', $route->checkpoints[0]?->agency?->city?->area?->id);
            })->get();
        }
<<<<<<< HEAD
        $fleets = FleetDetail::orderBy('fleet_id', 'ASC')->get();
        $route_fleets = FleetRoute::where('route_id', $route->id)->get();
        $statuses = Agency::status();
        return view('routes.show', compact('route', 'agencies', 'checkpoints', 'fleets', 'route_fleets', 'statuses'));
=======
        
        $fleets = FleetDetail::orderBy('fleet_id', 'ASC')->get();
        $statuses = Agency::status();

        $checkpoints_ = Checkpoint::where('route_id', $route->id)->orderBy('order')->get();
        $route_fleets_ = FleetRoute::where('route_id', $route->id)->get();
        if($request->duplicate == 1){
            $route = Route::create(['name' => $route->name]);
            foreach ($checkpoints_ as $checkpoint) {
                $checkpoints[] = Checkpoint::create([
                    'route_id' => $route->id,
                    'agency_id' => $checkpoint->agency_id,
                    'order' => $checkpoint->order,
                ]);
            }
            
            // $checkpoint_id = Checkpoint::where('route_id', $route->id)->get(['agency_id'])->toArray();
            foreach ($route_fleets_ as $route_fleet) {
                $route_fleets[] = FleetRoute::create([
                    'fleet_detail_id' => $route_fleet->fleet_detail_id,
                    'route_id' => $route->id,
                    'is_active' => $route_fleet->is_active,
                ]);
            }
        }
        session()->flash('success', 'Route Berhasil Ditambahkan');
        return redirect()->back();
>>>>>>> rilisv1
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
<<<<<<< HEAD
        $areas = Area::all();
        $cities = City::all();
        $fleets = Fleet::all();
        return view('routes.create', compact('areas', 'cities', 'route', 'fleets'));
=======
        $checkpoints = Checkpoint::where('route_id', $route->id)->orderBy('order')->get();
        $checkpoint_id = Checkpoint::where('route_id', $route->id)->get(['agency_id'])->toArray();
        if ($route->checkpoints->count() == 0) {
            $agencies = Agency::all();
        } else {
            $agencies = Agency::whereHas('city', function ($q) use ($route) {
                $q->where('area_id', $route->checkpoints[0]?->agency?->city?->area?->id);
            })
            ->whereNotIn('id', $route->checkpoints()->pluck('agency_id'))
            ->get();
        }
        return view('routes.edit', compact('route', 'agencies', 'checkpoints'));
>>>>>>> rilisv1
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
        return redirect(route('routes.show', $route->id));
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
<<<<<<< HEAD
        $route->checkpoints()->delete();
        $route->delete();
        session()->flash('success', 'Route Berhasil Dihapus');
        return redirect(route('routes.index'));
=======
        try {
            $route->checkpoints()->delete();
            $route->delete();
        } catch (\Throwable $th) {
            //throw $th;
        }
        // session()->flash('success', 'Route Berhasil Dihapus');
        // return redirect()->back();
>>>>>>> rilisv1
    }
}
