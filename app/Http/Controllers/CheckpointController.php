<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkpoint\CreateCheckpointRequest;
use App\Models\Checkpoint;
use Illuminate\Http\Request;

class CheckpointController extends Controller
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
        return view('checkpoint.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCheckpointRequest $request)
    {
        $data = $request->all();
        $data['route_id'] = $request->route_id;
        $checkpoint = Checkpoint::where('route_id', $request->route_id)->get();
        foreach ($checkpoint as $c) {
            dd($c->agency->name);
        }
        Checkpoint::create($data);
        session()->flash('success', 'Checkpoint Berhasil Ditambahkan');
        return redirect(route('routes.show', $request->route_id));
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
        //
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
    public function destroy(Checkpoint $checkpoint)
    {
        $checkpoint->delete();
        session()->flash('success', 'Checkpoint Berhasil Dihapus');
        return redirect()->back();
    }
}
