<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyFleet;
use App\Models\AgencyFleetPermanent;
use App\Models\Area;
use App\Models\Fleet;
use Illuminate\Http\Request;

class AgencyFleetPermanentController extends Controller
{

    public function create()
    {
        $agencies = Agency::whereHas('city.area', function ($query)
        {
            $query->where('id', 2);
        })->get();
        $fleets = Fleet::get();
        return view('agency_fleet.create-permanent', compact('agencies', 'fleets'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $agency_fleet_permanent = AgencyFleetPermanent::where('agency_id', $request->agency_id)->where('fleet_id', $request->fleet_id)->first();
        $agency_fleet = AgencyFleet::where('agency_id', $request->agency_id)->where('fleet_id', $request->fleet_id)->first();
        if($agency_fleet){
            session()->flash('error', 'Data sudah Ditambahkan');
        }else{
            if($agency_fleet_permanent){
                session()->flash('error', 'Data sudah Ditambahkan');
            }else{
                AgencyFleetPermanent::create($data);
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
        $agency_fleet_permanents = AgencyFleetPermanent::where('fleet_id', $id)->get();
        return view('agency_fleet.create-permanent', compact('agency_fleet_permanents', 'agencies', 'fleet'));
    }

    public function update(Request $request, AgencyFleetPermanent $agency_fleet_permanent)
    {
        $data = $request->all();
        $agency_fleet_permanent->update($data);
        session()->flash('success', 'Data Berhasil Dirubah');
        return redirect(route('agency_fleet.index'));
    }

    public function destroy(AgencyFleetPermanent $agency_fleet_permanent)
    {
        $agency_fleet_permanent->delete();
        session()->flash('success', 'Data Berhasil Dihapus');
        return redirect()->back();
    }
}
