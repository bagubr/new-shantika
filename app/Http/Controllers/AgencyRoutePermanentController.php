<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyRoute;
use App\Models\AgencyRoutePermanent;
use App\Models\Route;
use Illuminate\Http\Request;

class AgencyRoutePermanentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        $agency_fleet_permanent = AgencyRoutePermanent::where('agency_id', $request->agency_id)->where('route_id', $request->route_id)->first();
        $agency_fleet = AgencyRoute::where('agency_id', $request->agency_id)->where('route_id', $request->route_id)->first();
        if($agency_fleet){
            session()->flash('error', 'Data sudah Ditambahkan');
        }else{
            if($agency_fleet_permanent){
                session()->flash('error', 'Data sudah Ditambahkan');
            }else{
                AgencyRoutePermanent::create($data);
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
        $agency_route_permanents = AgencyRoutePermanent::where('route_id', $id)->get();
        return view('agency_route.create-permanent', compact('agency_route_permanents', 'agencies', 'route'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgencyRoutePermanent $agency_fleet_permanent)
    {
        $data = $request->all();
        $agency_fleet_permanent->update($data);
        session()->flash('success', 'Data Berhasil Dirubah');
        return redirect(route('agency_fleet.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgencyRoutePermanent $agency_route_permanent)
    {
        $agency_route_permanent->delete();
        session()->flash('success', 'Data Berhasil Dihapus');
        return redirect()->back();
    }
}
