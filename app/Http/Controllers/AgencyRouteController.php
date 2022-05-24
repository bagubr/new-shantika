<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyFleet;
use App\Models\AgencyRoute;
use App\Models\AgencyRoutePermanent;
use App\Models\Area;
use App\Models\Route;
use App\Repositories\RoutesRepository;
use Illuminate\Http\Request;

class AgencyRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $area_id = $request->area_id;
        $routes = Route::query();
        $areas = Area::all();
        if (!empty($area_id)) {
            $routes = $routes->whereHas('checkpoints.agency', function ($q) use ($area_id) {
                $q->whereHas('city', function ($sq) use ($area_id) {
                    $sq->where('area_id', $area_id);
                });
            });
        }
        $routes   = $routes->get();
        if (!$routes->isEmpty()) {
            session()->flash('success', 'Data Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
        return view('agency_route.index', compact('routes', 'areas', 'area_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $agencies = Agency::get();
        $routes = Route::get();
        return view('agency_route.create', compact('agencies', 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $agency_route = AgencyRoute::where('agency_id', $request->agency_id)->where('route_id', $request->route_id)->first();
        $agency_route_permanent = AgencyRoutePermanent::where('agency_id', $request->agency_id)->where('route_id', $request->route_id)->first();
        if($agency_route_permanent){
            session()->flash('error', 'Data sudah Ditambahkan');
        }else{
            if($agency_route){
                session()->flash('error', 'Data sudah Ditambahkan');
            }else{
                AgencyRoute::create($data);
                session()->flash('success', 'Data Berhasil Ditambahkan');
            }
        }
        return redirect()->back();
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
    public function edit($id)
    {
        $route = Route::find($id);
        $area_id = $route->checkpoints[0]?->agency?->city?->area?->id;
        $agencies = Agency::whereDoesntHave('agency_route', function ($query) use ($id)
        {
            $query->where('route_id', $id);
        })
        ->whereDoesntHave('agency_route_permanent', function ($query) use ($id)
        {
            $query->where('route_id', $id);
        })
        ->whereHas('city.area', function ($query) use ($area_id)
        {
            $query->where('id', '!=', $area_id);
        })
        ->get();
        $agency_routes = AgencyRoute::where('route_id', $id)->get();
        return view('agency_route.create', compact('agency_routes', 'agencies', 'route'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgencyRoute $agency_route)
    {
        $data = $request->all();
        $agency_route->update($data);
        session()->flash('success', 'Data Berhasil Dirubah');
        return redirect(route('agency_route.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgencyRoute $agency_route)
    {
        $agency_route->delete();
        session()->flash('success', 'Data Berhasil Dihapus');
        return redirect()->back();
    }
}
