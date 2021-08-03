<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkpoint\CreateCheckpointRequest;
use App\Models\Checkpoint;
use App\Models\Route;
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
        Checkpoint::create($data);
        $checkpoint = Checkpoint::where('route_id', $request->route_id)->get();
        $route = Route::whereId($request->route_id)->first();
        $checkpoints = '';
        foreach ($checkpoint as $c) {
            $checkpoints .= '~' . $c->agency()->first()->name . '~';
        }
        $route->update([
            'name' => $checkpoints,
        ]);
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
        $checkpointt = Checkpoint::where('route_id', $checkpoint->route_id)->get();
        $route = Route::whereId($checkpoint->route_id)->first();
        $checkpoints = '';
        foreach ($checkpointt as $c) {
            $checkpoints .= '~' . $c->agency()->first()->name . '~';
        }
        $route->update([
            'name' => $checkpoints,
        ]);
        session()->flash('success', 'Checkpoint Berhasil Dihapus');
        return back();
    }
}
