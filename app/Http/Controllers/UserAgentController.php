<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAgent\CreateUserAgentRequest;
use App\Http\Requests\UserAgent\UpdateUserAgentRequest;
use App\Models\Area;
use App\Models\Order;
use App\Models\User;
use App\Models\UserAgent;
use App\Repositories\AgencyRepository;
use Illuminate\Http\Request;

class UserAgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereHas('agencies')->get();
        $agencies = AgencyRepository::all_order();
        $areas = Area::all();
        return view('user_agent.index', compact('users', 'agencies', 'areas'));
    }
    public function search(Request $request)
    {
        $name_search = $request->name;
        $agent = $request->agent;
        $area_id = $request->area_id;
        $users = User::query();
        $agencies = AgencyRepository::all_order();
        $areas = Area::all();

        if (!empty($agent)) {
            $users = $users->whereHas('agencies', function ($q) use ($agent) {
                $q->where('agency_id', 'like', $agent);
            });
        }
        if (!empty($name_search)) {
            $users = $users->where('name', 'ilike', '%' . $name_search . '%')->whereHas('agencies');
        }
        if (!empty($area_id)) {
            $users = $users->whereHas('agencies.agent', function ($q) use ($area_id) {
                $q->whereHas('city', function ($sq) use ($area_id) {
                    $sq->where('area_id', $area_id);
                });
            });
        }
        // $users = $users->whereHas('agencies');
        $test = $request->flash();
        $users = $users->has('agencies')->get();
        if (!$users->isEmpty()) {
            session()->flash('success', 'Data Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
        return view('user_agent.index', compact('users', 'agencies', 'test', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $genders = ['Male', 'Female'];
        $agencies = AgencyRepository::all_order();
        return view('user_agent.create', compact('genders', 'agencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserAgentRequest $request)
    {
        $data = $request->except(['agency_id']);
        $number = $request->phone;
        $country_code = '62';
        $isZero = substr($number, 0, 1);
        if ($isZero == '0') {
            $new_number = substr_replace($number, '+' . $country_code, 0, ($number[0] == '0'));
            $data['phone'] = $new_number;
        } else {
            $data['phone'] = $number;
        }
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->avatar->store('avatar', 'public');
        }
        $user = User::create($data);
        UserAgent::create([
            'user_id' => $user->id,
            'agency_id' => $request->agency_id
        ]);
        session()->flash('success', 'User Agent Berhasil Ditambahkan');
        return redirect(route('user_agent.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user_agent)
    {
        $orders = Order::where('user_id', $user_agent->id)->get();
        return view('user_agent.show', compact('user_agent', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user_agent)
    {
        $agencies = AgencyRepository::all_order();
        $genders = ['Male', 'Female'];
        return view('user_agent.create', compact('user_agent', 'genders', 'agencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserAgentRequest $request, User $user_agent)
    {
        $data = $request->only(['name', 'phone', 'email', 'birth_place', 'birth', 'address', 'gender']);
        $number = $request->phone;
        $country_code = '62';
        $isZero = substr($number, 0, 1);
        if ($isZero == '0') {
            $new_number = substr_replace($number, '+' . $country_code, 0, ($number[0] == '0'));
            $data['phone'] = $new_number;
        } else {
            $data['phone'] = $number;
        }
        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar->store('avatar', 'public');
            $user_agent->deleteAvatar();
            $data['avatar'] = $avatar;
        };
        $user_agent->update($data);
        if ($request->agency_id == "") {
            $user_agent->agencies->delete();
        } else {
            $user_agent->agencies->update([
                'agency_id' => $request->agency_id
            ]);
        }
        session()->flash('success', 'User Agent Berhasil Diperbarui');
        return redirect(route('user_agent.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user_agent)
    {
        $user_agent->deleteAvatar();
        if ($user_agent->agencies) {
            $user_agent->agencies->delete();
        }
        $user_agent->delete();
        session()->flash('success', 'User Agent Berhasil Dihapus');
        return redirect(route('user_agent.index'));
    }
}
