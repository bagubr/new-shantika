<?php

namespace App\Http\Controllers;

use App\Http\Requests\Agency\CreateAgencyRequest;
use App\Http\Requests\Agency\UpdateAgencyRequest;
use App\Models\Agency;
use App\Repositories\AgencyRepository;
use App\Repositories\CityRepository;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agencies = AgencyRepository::all();
        $statuses = Agency::status();
        return view('agency.index', compact('agencies', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = CityRepository::all();
        return view('agency.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAgencyRequest $request)
    {
        $data = $request->all();
        $data['is_active'] = 1;
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->avatar->store('avatar', 'public');
        }
        Agency::create($data);
        session()->flash('success', 'Agency Berhasil Ditambahkan');
        return redirect(route('agency.index'));
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
    public function edit(Agency $agency)
    {
        $cities = CityRepository::all();
        return view('agency.create', compact('agency', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAgencyRequest $request, Agency $agency)
    {
        $data = $request->all();
        $agency->update($data);
        session()->flash('success', 'Agency Berhasil Diperbarui');
        return redirect(route('agency.index'));
    }
    public function update_status(Request $request, Agency $agency)
    {
        $agency->update([
            'is_active' => $request->is_active,
        ]);
        session()->flash('success', 'Agency Status Berhasil Diubah');
        return redirect(route('agency.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agency $agency)
    {
        $agency->delete();
        session()->flash('success', 'Agency Berhasil Dihapus');
        return redirect(route('agency.index'));
    }
}
