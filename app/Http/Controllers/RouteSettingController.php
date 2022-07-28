<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Area;
use App\Models\FleetRoute;
use App\Models\RouteSetting;
use App\Repositories\FleetRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $area_id = Auth::user()->area_id??$request->area_id;
        $fleet_id = $request->fleet_id;

        $fleet_routes = FleetRoute::when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('route.checkpoints.agency.city', function ($query) use ($area_id) {
                    $query->where('area_id', $area_id);
            });
        })->when($fleet_id, function ($query) use ($fleet_id)
        {
            $query->whereHas('fleet_detail.fleet', function ($query) use ($fleet_id) {
                $query->where('fleet_id', $fleet_id);
            });
        })->orderBy('id', 'desc')->get();
        $areas = Area::get();
        $fleets = FleetRepository::all();

        return view('route_setting.index', compact('fleet_routes', 'fleet_id', 'area_id', 'areas', 'fleets'));
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
        $route_setting = RouteSetting::where('fleet_route_id', $data['fleet_route_id'])->where('agency_id', $data['agency_id'])->first();
        if($route_setting){
            return redirect()->back()->with('success', 'Data sudah ditambahkan');
        }
        RouteSetting::create($data);
        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
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
        $fleet_route = FleetRoute::find($id);
        $agencies = Agency::whereDoesntHave('route_setting')
        ->whereHas('city', function ($query) use ($fleet_route)
        {
            $query->where('area_id', $fleet_route->route->checkpoints[0]->agency->city->area_id);
        })->get();
        $route_settings = RouteSetting::where('fleet_route_id', $fleet_route->id)->get();
        return view('route_setting.edit', compact('fleet_route', 'route_settings', 'agencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $route_setting = RouteSetting::find($id);
        $route_setting->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
        

    }
}
