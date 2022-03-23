<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyRoute;
use App\Models\Route;
use Illuminate\Http\Request;

class AgencyRouteController extends Controller
{
    public function index()
    {
        $agency_routes = AgencyRoute::get();
        return view('agency_route.index', compact('agency_routes'));
    }

    public function create()
    {
        $agencies = Agency::whereHas('city.area', function ($query)
        {
            $query->where('id', 2);
        })->get();
        $routes = Route::get();
        return view('agency_route.create', compact('agencies', 'routes'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $agency_route = AgencyRoute::where('agency_id', $request->agency_id)->where('route_id', $request->route_id)->first();
        if($agency_route){
            session()->flash('error', 'Data sudah Ditambahkan');
        }else{
            AgencyRoute::create($data);
            session()->flash('success', 'Data Berhasil Ditambahkan');
        }
        return redirect(route('agency_route.index'));
    }

    public function edit(AgencyRoute $agency_route)
    {
        $agencies = Agency::whereHas('city.area', function ($query)
        {
            $query->where('id', 2);
        })->get();
        $routes = Route::get();
        return view('agency_route.create', compact('agency_route', 'agencies', 'routes'));
    }

    public function update(Request $request, AgencyRoute $agency_route)
    {
        $data = $request->all();
        $agency_route->update($data);
        session()->flash('success', 'Data Berhasil Dirubah');
        return redirect(route('agency_route.index'));
    }

    public function destroy(AgencyRoute $agency_route)
    {
        $agency_route->delete();
        session()->flash('success', 'Data Berhasil Dihapus');
        return redirect(route('agency_route.index'));
    }
}
