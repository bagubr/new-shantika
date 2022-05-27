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
    public function index()
    {
        $fleets = Fleet::get();
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
        $agency_fleet = AgencyFleet::where('agency_id', $request->agency_id)->where('fleet_id', $request->fleet_id)->first();
        $agency_fleet_permanent = AgencyFleetPermanent::where('agency_id', $request->agency_id)->where('fleet_id', $request->fleet_id)->first();
        if($agency_fleet_permanent){
            session()->flash('error', 'Data sudah Ditambahkan');
        }else{
            if($agency_fleet){
                session()->flash('error', 'Data sudah Ditambahkan');
            }else{
                AgencyFleet::create($data);
                session()->flash('success', 'Data Berhasil Ditambahkan');
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
        $agency_fleets = AgencyFleet::where('fleet_id', $id)->get();
        return view('agency_fleet.create', compact('agency_fleets', 'agencies', 'fleet'));
    }

    public function update(Request $request, AgencyFleet $agency_fleet)
    {
        $data = $request->all();
        $agency_fleet->update($data);
        session()->flash('success', 'Data Berhasil Dirubah');
        return redirect(route('agency_fleet.index'));
    }

    public function destroy(AgencyFleet $agency_fleet)
    {
        $agency_fleet->delete();
        session()->flash('success', 'Data Berhasil Dihapus');
        return redirect()->back();
    }
}
