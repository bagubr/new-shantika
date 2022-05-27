<?php

namespace App\Http\Controllers;

use App\Http\Requests\City\CreateCityRequest;
use App\Models\Area;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::all();
        return view('city.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas = Area::all();
        $provinces = Province::all();
        return view('city.create', compact('provinces', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCityRequest $request)
    {
        $data = $request->all();
        City::create($data);
        session()->flash('success', 'Kota Berhasil Ditambahkan');
        return redirect(route('city.index'));
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
    public function edit(City $city)
    {
        $provinces = Province::all();
        $areas = Area::all();
        return view('city.create', compact('city', 'provinces', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateCityRequest $request, City $city)
    {
        $data = $request->all();
        $city->update($data);
        session()->flash('success', 'Kota Berhasil Diubah');
        return redirect(route('city.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        // if ($city->agent->count() > 0) {
        //     session()->flash('error', 'Maaf Anda Tidak Bisa Menghapus Data Ini');
        //     return redirect()->back();
        // }
        $city->agent()->delete();
        $city->delete();
        session()->flash('success', 'Kota Berhasil Dihapus');
        return redirect(route('city.index'));
    }
}
