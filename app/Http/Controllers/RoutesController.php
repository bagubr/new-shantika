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
use Illuminate\Http\Request;

class RoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $area_id = $request->area_id;
        
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
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoutesRequest $request)
    {
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
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        $checkpoints = Checkpoint::where('route_id', $route->id)->orderBy('order')->get();
        return view('routes.show', compact('route', 'checkpoints'));
    }

    public function duplicate(Route $route, Request $request)
    {
        
        if ($route->checkpoints->count() == 0) {
            $agencies = Agency::all();
        } else {
            $agencies = Agency::whereHas('city', function ($q) use ($route) {
                $q->where('area_id', $route->checkpoints[0]?->agency?->city?->area?->id);
            })->get();
        }
        
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
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
        try {
            $route->checkpoints()->delete();
            $route->delete();
        } catch (\Throwable $th) {
            //throw $th;
        }
        // session()->flash('success', 'Route Berhasil Dihapus');
        // return redirect()->back();
    }
}
