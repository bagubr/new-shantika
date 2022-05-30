<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FleetDetail;
use App\Models\Fleet;
use App\Models\TimeClassification;

class FleetDetailController extends Controller
{
    public function index()
    {
        $fleet_detail = FleetDetail::with('time_classification')->paginate(10);
        return view('fleet_detail.index', compact('fleet_detail'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'fleet_id'                  => 'required|exists:fleets,id',
            'plate_number'              => 'required|string',
            'nickname'                  => 'required|string',
            'time_classification_id'    => 'required',
            'co_driver'                 => 'string'
        ]);
        FleetDetail::create($data);
        return redirect()->route('fleets.index')->with('success', 'Data berhasil di tambahkan');
    }
    
    public function update(Request $request, $id)
    {
        $fleet_detail = FleetDetail::find($id);
        $data = $this->validate($request, [
            'fleet_id'                  => 'required|exists:fleets,id',
            'plate_number'              => 'required|string',
            'nickname'                  => 'required|string',
            'time_classification_id'    => 'required',
            'co_driver'                 => 'string'
        ]);
        $fleet_detail->update($data);
        return redirect('fleets/'.$fleet_detail->fleet_id)->with('success', 'Data berhasil di update');
    }
    
    public function edit($id)
    {
        $fleet_detail = FleetDetail::find($id);
        $time_classifications = TimeClassification::get();
        $fleets = Fleet::orderBy('id', 'desc')->get();
        return view('fleet_detail.edit', compact('fleet_detail', 'fleets', 'time_classifications'));
    }

    public function create(Request $request)
    {
        $fleets = Fleet::orderBy('id', 'desc')->get();
        $time_classifications = TimeClassification::get();
        $fleet_id = $request->fleet_id;
        return view('fleet_detail.create', compact('fleets', 'time_classifications', 'fleet_id'));
    }

    public function destroy($id)
    {
        $fleet_detail = FleetDetail::find($id);
        $fleet_detail->delete();
        return redirect()->back()->with('danger', 'Data berhasil di hapus');
    }
}
