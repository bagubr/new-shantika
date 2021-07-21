<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Agency;
use App\Models\User;
use App\Models\UserAgent;
use App\Repositories\CityRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
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
        return view('user.create', compact('genders', 'agencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
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
        session()->flash('success', 'User Berhasil Ditambahkan');
        return redirect(route('user.index'));
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
    public function edit(User $user)
    {
        $genders = ['Male', 'Female'];
        $agencies = Agency::all();
        return view('user.create', compact('user', 'genders', 'agencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->only(['name', 'phone', 'email', 'birth_place', 'birth', 'address', 'gender']);
        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar->store('avatar', 'public');
            $user->deleteAvatar();
            $data['avatar'] = $avatar;
        };
        $user->update($data);
        if ($request->agency_id == "") {
            $user->agencies->delete();
        } else {
            $user->agencies->update([
                'agency_id' => $request->agency_id
            ]);
        }
        session()->flash('success', 'User Berhasil Diperbarui');
        return redirect(route('user.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->deleteAvatar();
        if ($user->agencies) {
            $user->agencies->delete();
        }
        $user->delete();
        session()->flash('success', 'User Berhasil Dihapus');
        return redirect(route('user.index'));
    }
}
