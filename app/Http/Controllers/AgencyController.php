<?php

namespace App\Http\Controllers;

use App\Http\Requests\Agency\CreateAgencyRequest;
use App\Http\Requests\Agency\UpdateAgencyRequest;
use App\Models\Agency;
use App\Models\AgencyDepartureTime;
use App\Models\Area;
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
        $areas = Area::get();
        return view('agency.index', compact('agencies', 'statuses', 'areas'));
    }
    public function search(Request $request)
    {
        $area_id  = $request->area_id;
        $agencies   = Agency::query();
        $statuses = Agency::status();
        $areas = Area::get();


        if (!empty($area_id)) {
            $agencies = $agencies->whereHas('city', function ($q) use ($area_id) {
                $q->where('area_id', $area_id);
            });
        }
        $test       = $request->flash();
        $agencies   = $agencies->get();

        if (!$agencies->isEmpty()) {
            session()->flash('success', 'Data Order Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
        return view('agency.index', compact('agencies', 'statuses', 'test', 'areas'));
    }
    public function get_agency(Request $request)
    {
        $area_id = $request->area_id;
        $agencies = Agency::query();
        if (!empty($area_id)) {
            $agencies = $agencies->whereHas('city', function ($q) use ($area_id) {
                $q->where('area_id', $area_id);
            });
        }
        $agencies = $agencies->get();
        return response([
            'agencies' => $agencies
        ]);
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
        $agency = Agency::create($data);
        foreach ($request->departure_at as $key => $value) {
            AgencyDepartureTime::create([
                'agency_id'     => $agency->id,
                'departure_at'  => $value,
            ]);
        }
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
        $agency_departure = AgencyDepartureTime::where('agency_id', $agency->id)->get();
        return view('agency.create', compact('agency', 'cities', 'agency_departure'));
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
        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar->store('avatar', 'public');
            $agency->deleteAvatar();
            $data['avatar'] = $avatar;
        };
        $agency_departure = AgencyDepartureTime::where('agency_id', $agency->id)->first();
        $agency_departure1 = AgencyDepartureTime::where('agency_id', $agency->id)->orderBy('id', 'desc')->first();
        $agency->update($data);
        $agency_departure->update([
            'agency_id'     => $agency->id,
            'departure_at'  => $request->departure_at[0],
        ]);
        $agency_departure1->update([
            'agency_id'     => $agency->id,
            'departure_at'  => $request->departure_at[1],
        ]);
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
        $agency->deleteAvatar();
        $agency->delete();
        session()->flash('success', 'Agency Berhasil Dihapus');
        return redirect(route('agency.index'));
    }
}
