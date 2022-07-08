<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyFleet;
use App\Models\AgencyFleetPermanent;
use App\Models\Area;
use App\Models\Fleet;
use App\Models\Route;
use Illuminate\Http\Request;

class AgencyFleetController extends Controller
{
    public function index(Request $request)
    {
        $fleets = Fleet::when($request->area_id, function ($query) use ($request)
        {
            $query->whereHas('fleet_detail.fleet_route.route.checkpoints.agency.city', function ($query) use ($request)
            {
                $query->where('area_id', '!=', $request->area_id);
            } );
        })->get();
        $areas = Area::all();
        return view('agency_fleet.index', compact('fleets'));
    }

    public function create()
    {
        $agencies = Agency::whereHas('city.area', function ($query)
        {
            $query->where('id', 2);
        })->get();
        $fleets = Fleet::get();
        return view('agency_fleet.create', compact('agencies', 'fleets'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $agency_fleet = AgencyFleetPermanent::where('agency_id', $request->agency_id)->where('fleet_id', $request->fleet_id)->first();
        $agency_fleet_permanent = AgencyFleetPermanent::where('agency_id', $request->agency_id)->where('fleet_id', $request->fleet_id)->first();
        if($agency_fleet_permanent){
            session()->flash('error', 'Data sudah Ditambahkan');
        }else{
            if($agency_fleet){
                session()->flash('error', 'Data sudah Ditambahkan');
            }else{
                if(AgencyFleetPermanent::whereFleetId($request->fleet_id)->count() <= 0){
                    session()->flash('error', 'Tambahkan setidaknya 1 agent permanent untuk menggunakan fitur temporary');
                }else{
                    AgencyFleetPermanent::create($data);
                    session()->flash('success', 'Data Berhasil Ditambahkan');
                }
            }
        }
        return redirect()->back();
    }

    public function edit($id)
    {
        $fleet = Fleet::find($id);
        $agencies = Agency::whereDoesntHave('agency_fleet', function ($query) use ($id)
        {
            $query->where('fleet_id', $id);
        })
        ->whereDoesntHave('agency_fleet_permanent', function ($query) use ($id)
        {
            $query->where('fleet_id', $id);
        })
        ->get();
        $agency_fleets = AgencyFleetPermanent::where('start_at', '!=', null)->where('end_at', '!=', null)->where('fleet_id', $id)->get();
        return view('agency_fleet.create', compact('agency_fleets', 'agencies', 'fleet'));
    }

    public function update(Request $request, AgencyFleetPermanent $agency_fleet)
    {
        $data = $request->all();
        $agency_fleet->update($data);
        session()->flash('success', 'Data Berhasil Dirubah');
        return redirect(route('agency_fleet.index'));
    }

    public function destroy(AgencyFleetPermanent $agency_fleet)
    {
        $agency_fleet->delete();
        session()->flash('success', 'Data Berhasil Dihapus');
        return redirect()->back();
    }
}
