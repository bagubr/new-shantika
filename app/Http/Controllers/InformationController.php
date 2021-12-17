<?php

namespace App\Http\Controllers;

use App\Http\Requests\Information\CreateInformationRequest;
use App\Http\Requests\Information\UpdateInformationRequest;
use App\Models\Information;
use App\Repositories\InformationRepository;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $informations = InformationRepository::all();
        return view('information.index', compact('informations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('information.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateInformationRequest $request)
    {
        $data = $request->all();
        $data['image'] = $request->image->store('information', 'public');
        Information::create($data);
        session()->flash('success', 'Information Berhasil Ditambahkan');
        return redirect(route('information.index'));
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
    public function edit(Information $information)
    {
        return view('information.create', compact('information'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInformationRequest $request, Information $information)
    {
        $data = $request->only(['name', 'address', 'description']);
        if ($request->hasFile('image')) {
            $image = $request->image->store('information', 'public');
            $information->deleteImage();
            $data['image'] = $image;
        }
        $information->update($data);
        session()->flash('success', 'Information Berhasil Diperbarui');
        return redirect(route('information.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $information = InformationRepository::deleteId($id);
        $information->deleteImage();
        $information->delete();
        session()->flash('success', 'Information Berhasil Dihapus');
        return redirect(route('information.index'));
    }
}
