<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFleetClassRequest;
use App\Http\Requests\FleetClass\UpdateFleetClassRequest;
use App\Models\FleetClass;
use App\Repositories\FleetClassRepository;
use Illuminate\Http\Request;

class FleetClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fleetclasses = FleetClassRepository::all();
        return view('fleetclass.index', compact('fleetclasses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fleetclass.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFleetClassRequest $request)
    {
        $data = $request->all();
        FleetClass::create($data);
        session()->flash('success', 'Armada Kelas Berhasil Ditambahkan');
        return redirect(route('fleetclass.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FleetClass $fleetclass)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FleetClass $fleetclass)
    {
        return view('fleetclass.create', compact('fleetclass'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFleetClassRequest $request, FleetClass $fleetclass)
    {
        $data = $request->all();
        $fleetclass->update($data);
        session()->flash('success', 'Fleet Class Berhasil Diperbarui');
        return redirect(route('fleetclass.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fleetclass = FleetClassRepository::deleteId($id);
        if ($fleetclass->trashed()) {
            $fleetclass->forceDelete();
            session()->flash('success', 'Fleet Class Berhasil Dihapus');
        } else {
            $fleetclass->delete();
            session()->flash('success', 'Fleet Class Berhasil Diarsip');
        }
        return redirect(route('fleetclass.index'));
    }
}
