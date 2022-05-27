<?php

namespace App\Http\Controllers;

use App\Http\Requests\Province\CreateProvinceRequest;
use App\Http\Requests\Province\UpdateProvinceRequest;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = Province::all();
        return view('province.index', compact('provinces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('province.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProvinceRequest $request)
    {
        $data = $request->all();
        Province::create($data);
        session()->flash('success', 'Provinsi Berhasil Ditambahkan');
        return redirect(route('province.index'));
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
    public function edit(Province $province)
    {
        return view('province.create', compact('province'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProvinceRequest $request, Province $province)
    {
        $data = $request->all();
        $province->update($data);
        session()->flash('success', 'Provinsi Berhasil Diubah');
        return redirect(route('province.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $province)
    {
        if ($province->cities->count() > 0) {
            session()->flash('error', 'Maaf Anda Tidak Bisa Menghapus Data Ini');
            return redirect()->back();
        }
        $province->agencies()->delete();
        $province->cities()->delete();
        $province->delete();
        session()->flash('success', 'Provinsi Berhasil Dihapus');
        return redirect(route('province.index'));
    }
}
