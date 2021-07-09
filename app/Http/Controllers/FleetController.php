<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fleet\CreateFleetRequest;
use App\Models\Fleet;
use App\Repositories\FleetClassRepository;
use App\Repositories\FleetRepository;
use Illuminate\Http\Request;

class FleetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fleets = FleetRepository::all();
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
        return view('fleet.create', compact('fleetclasses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFleetRequest $request)
    {
        $image = $request->file('image');
        $data = $request->all();
        $data['layout_id'] = 1;
        $data['image'] = $image->store('fleet');

        Fleet::create($data);
        session()->flash('success', 'Fleet Created Successfully');
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
        //
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
        return view('fleet.create', compact('fleet', 'fleetclasses'));
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
        //
    }
}
