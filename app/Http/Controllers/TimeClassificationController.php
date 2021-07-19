<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeClassification\CreateTimeClassificationRequest;
use App\Models\TimeClassification;
use Illuminate\Http\Request;

class TimeClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $time_classifications = TimeClassification::all();
        return view('time_classification.index', compact('time_classifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('time_classification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTimeClassificationRequest $request)
    {
        $data = $request->all();
        TimeClassification::create($data);

        session()->flash('success', 'Waktu Berhasil Ditambahkan');
        return redirect(route('time_classification.index'));
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
    public function edit(TimeClassification $time_classification)
    {
        return view('time_classification.create', compact('time_classification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTimeClassificationRequest $request, TimeClassification $time_classification)
    {
        $data = $request->all();
        $time_classification->update($data);

        session()->flash('success', 'Waktu Berhasil Dirubah');
        return redirect(route('time_classification.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeClassification $time_classification)
    {
        $time_classification->delete();
        session()->flash('success', 'Waktu Berhasil Dihapus');
        return redirect(route('time_classification.index'));
    }
}
