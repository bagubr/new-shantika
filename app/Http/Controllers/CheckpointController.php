<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkpoint\CreateCheckpointRequest;
use App\Models\Agency;
use App\Models\Checkpoint;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $checkpoint = Checkpoint::create($data);
        $route = Route::find($data['route_id']);
        $route->name .= '~' . Agency::find($request->agency_id)->name . '~';
        $route->update([
            'name' => $route->name
        ]);
        session()->flash('success', 'Route Berhasil Ditambahkan');
        return redirect()->back();
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
        $checkpointt = Checkpoint::where('route_id', $checkpoint->route_id)->orderBy('order', 'ASC')->get();
        $route = Route::whereId($checkpoint->route_id)->first();
        $checkpoints = '';
        foreach ($checkpointt as $c) {
            $checkpoints .= '~' . $c->agency()->first()->name . '~';
        }
        $route->update([
            'name' => $checkpoints,
        ]);
        session()->flash('success', 'Checkpoint Berhasil Dihapus');
        return redirect()->back();
    }
}
