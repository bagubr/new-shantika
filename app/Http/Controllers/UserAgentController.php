<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAgent\CreateUserAgentRequest;
use App\Http\Requests\UserAgent\UpdateUserAgentRequest;
use App\Models\Agency;
use App\Models\User;
use App\Models\UserAgent;
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
        return view('user_agent.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $genders = ['Male', 'Female'];
        $agencies = Agency::all();
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
    public function edit(User $user_agent)
    {
        $genders = ['Male', 'Female'];
        $agencies = Agency::all();
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
