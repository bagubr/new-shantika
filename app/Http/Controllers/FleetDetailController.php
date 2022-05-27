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
            'plate_number'              => 'required|array',
            'nickname'                  => 'required|array',
            'time_classification_id'    => 'required',
            'plate_number.*'            => 'required|string',
            'nickname.*'                => 'required|string',
        ]);
        for ($i=0; $i < count($data['plate_number']); $i++) { 
            $fleet_detail = [
                'fleet_id' => $data['fleet_id'],
                'plate_number' => $data['plate_number'][$i],
                'time_classification_id'=>$data['time_classification_id'][$i],
                'nickname' => $data['nickname'][$i],
            ];
            FleetDetail::create($fleet_detail);
        }
        return redirect()->back()->with('success', 'Data berhasil di tambahkan');
    }
    
    public function update(Request $request, $id)
    {
        $fleet_detail = FleetDetail::find($id);
        $data = $this->validate($request, [
        'fleet_id'                      => 'required|exists:fleets,id',
            'plate_number'              => 'required|string',
            'nickname'                  => 'required|string',
            'time_classification_id'    => 'required'
        ]);
        $fleet_detail->update($data);
        return redirect('fleets/'.$fleet_detail->fleet_id)->with('success', 'Data berhasil di update');
    }
    
    public function edit($id)
    {
        $fleet_detail = FleetDetail::find($id);
        $time_classifications = TimeClassification::get();
        $fleets = Fleet::orderBy('id', 'desc')->get();
        return view('fleet.edit_fleet_detail', compact('fleet_detail', 'fleets', 'time_classifications'));
    }

    public function create()
    {
        $fleet = Fleet::orderBy('id', 'desc')->get();
        $time_classifications = TimeClassification::get();
        return view('fleet_detail', compact('fleet'));
    }

    public function destroy($id)
    {
        $fleet_detail = FleetDetail::find($id);
        $fleet_detail->delete();
        return redirect()->back()->with('danger', 'Data berhasil di hapus');
    }
}
