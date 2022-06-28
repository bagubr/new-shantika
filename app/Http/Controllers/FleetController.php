<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fleet\CreateFleetRequest;
use App\Http\Requests\Fleet\UpdateFleetRequest;
use App\Models\Fleet;
use App\Models\TimeClassification;
use App\Repositories\FleetClassRepository;
use App\Repositories\FleetRepository;
use App\Repositories\LayoutRepository;
use Illuminate\Support\Facades\Auth;

class FleetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $area_id            = Auth::user()->area_id;
        $fleets =  Fleet::with('layout')
        ->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('fleet_detail.fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        })
        ->get();
        return view('fleet.index', compact('fleets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fleetclasses = FleetClassRepository::all();
        $layouts = LayoutRepository::all();
        return view('fleet.create', compact('fleetclasses', 'layouts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFleetRequest $request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->image->store('image', 'public');
            $data['image'] = $image;
        };

        Fleet::create($data);
        session()->flash('success', 'Armada Berhasil Ditambahkan');
        return redirect(route('fleets.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fleet = Fleet::with('fleet_detail.time_classification')->find($id);
        $fleets = Fleet::get();
        $fleetclasses = FleetClassRepository::all();
        $layouts = LayoutRepository::all();
        $time_classifications = TimeClassification::get();
        return view('fleet.show', compact('fleets', 'fleetclasses', 'layouts', 'fleet', 'time_classifications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Fleet $fleet)
    {
        $fleetclasses = FleetClassRepository::all();
        $layouts = LayoutRepository::all();
        return view('fleet.create', compact('fleet', 'fleetclasses', 'layouts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFleetRequest $request, Fleet $fleet)
    {
        $data = $request->only(['name', 'layout_id', 'fleet_class_id', 'description']);
        if ($request->hasFile('image')) {
            $image = $request->image->store('image', 'public');
            $fleet->deleteImage();
            $data['image'] = $image;
        };
        $fleet->update($data);
        session()->flash('success', 'Armada Berhasil Diperbarui');
        return redirect(route('fleets.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fleet = FleetRepository::deleteId($id);
        if ($fleet->trashed()) {
            $fleet->deleteImage();
            $fleet->forceDelete();
            session()->flash('success', 'Armada Berhasil Dihapus');
        } else {
            $fleet->delete();
            session()->flash('success', 'Armada Berhasil Diarsip');
        }
        return redirect(route('fleets.index'));
    }
}
