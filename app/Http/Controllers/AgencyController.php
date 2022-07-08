<?php

namespace App\Http\Controllers;

use App\Http\Requests\Agency\CreateAgencyRequest;
use App\Http\Requests\Agency\UpdateAgencyRequest;
use App\Models\Agency;
use App\Models\AgencyDepartureTime;
use App\Models\Area;
use App\Models\TimeClassification;
use App\Repositories\AgencyRepository;
use App\Repositories\CityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $area_id = Auth::user()->area_id;
        $agencies = Agency::when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        })->with(['prices'=>function($query) {
            $query->whereDate('start_at', '<=', date('Y-m-d'));
        }])->orderBy('id')->paginate(10);
        $statuses = Agency::status();
        $areas = Area::get();
        return view('agency.index', compact('agencies', 'statuses', 'areas', 'area_id'));
    }
    public function search(Request $request)
    {
        $area_id  = Auth::user()->area_id??$request->area_id;
        $search  = $request->search;
        $agencies   = Agency::query();
        $statuses = Agency::status();
        $areas = Area::get();
        
        
        if (!empty($area_id)) {
            $agencies = $agencies->whereHas('city', function ($q) use ($area_id) {
                $q->where('area_id', $area_id);
            });
        }
        $agencies = $agencies
        ->with(['city' => function ($query) use ($search, $area_id)
        {
            $query->when($area_id, function ($que) use ($area_id)
            {
                $que->where('area_id', $area_id);
            });
        }])
        ->whereHas('city', function ($que) use ($search)
        {
            $que->where('name', 'ilike', '%'.$search.'%');
        })
        ->orWhere('name', 'ilike', '%'.$search.'%')
        ->orWhere('code', 'ilike', '%'.$search.'%')
        ->orWhere('phone', 'ilike', '%'.$search.'%')
        ->orWhere('address', 'ilike', '%'.$search.'%');
        
        $test       = $request->flash();
        $agencies   = $agencies->orderBy('id')->paginate(10);

        return view('agency.index', compact('agencies', 'statuses', 'test', 'areas', 'search', 'area_id'));
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
        $time_classifications = TimeClassification::orderBy('id')->get();
        return view('agency.create', compact('cities', 'time_classifications'));
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
        $data['is_agent_route'] = false;
        $data['is_route'] = false;
        $data['is_agent'] = false;
        if(isset($data['is_type']) && $data['is_type'] == 'is_agent_route'){
            $data['is_agent_route'] = true;
        }elseif(isset($data['is_type']) && $data['is_type'] == 'is_route'){
            $data['is_route'] = true;
        }elseif(isset($data['is_type']) && $data['is_type'] == 'is_agent'){
            $data['is_agent'] = true;
        }
        $data['is_active'] = 1;
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->avatar->store('avatar', 'public');
        }
        $agency = Agency::create($data);
        $time_classifications = TimeClassification::orderBy('id')->get();
        foreach ($request->departure_at as $key => $value) {
            AgencyDepartureTime::create([
                'agency_id'                 => $agency->id,
                'departure_at'              => $value,
                'time_classification_id'    => $time_classifications[$key]->id
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
        $time_classifications = TimeClassification::orderBy('id')->get();
        foreach ($time_classifications as $key => $value) {
            $time_classifications[$key]->agency_departure = AgencyDepartureTime::where('agency_id', $agency->id)->where('time_classification_id', $value->id)->orderBy('id')->limit(1)->get();
        }
        return view('agency.create', compact('agency', 'cities', 'time_classifications'));
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
        $data['is_agent_route'] = false;
        $data['is_route'] = false;
        $data['is_agent'] = false;
        if(isset($data['is_type']) && $data['is_type'] == 'is_agent_route'){
            $data['is_agent_route'] = true;
        }elseif(isset($data['is_type']) && $data['is_type'] == 'is_route'){
            $data['is_route'] = true;
        }elseif(isset($data['is_type']) && $data['is_type'] == 'is_agent'){
            $data['is_agent'] = true;
        }
        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar->store('avatar', 'public');
            $agency->deleteAvatar();
            $data['avatar'] = $avatar;
        };
        $time_classifications = TimeClassification::orderBy('id')->get();
        foreach ($time_classifications as $key => $value) {
            AgencyDepartureTime::updateOrCreate([
                'agency_id'=> $agency->id,
                'time_classification_id'=> $value->id
            ],
            [
                'departure_at' => $data['departure_at'][$key]
            ]);
        }
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
        $agency->deleteAvatar();
        $agency->delete();
        session()->flash('success', 'Agency Berhasil Dihapus');
        return redirect(route('agency.index'));
    }
}
