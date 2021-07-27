<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\CreateMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Models\Agency;
use App\Models\Membership;
use App\Models\User;
use App\Repositories\AgencyRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = Membership::all();
        return view('member.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = UserRepository::notAgent();
        $agencies = AgencyRepository::getOnlyIdName();
        return view('member.create', compact('users', 'agencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMemberRequest $request)
    {
        $last = Membership::latest()->first();
        $data = $request->all();
        $data['code_member'] = $last->code_member + 1;
        Membership::create($data);

        session()->flash('success', 'Member Berhasil Ditambahkan');
        return redirect(route('member.index'));
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
    public function edit(Membership $member)
    {
        $users = UserRepository::notAgent();
        $agencies = AgencyRepository::getOnlyIdName();
        return view('member.create', compact('member', 'users', 'agencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemberRequest $request, Membership $member)
    {
        $data = $request->all();
        $member->update($data);

        session()->flash('success', 'Member Berhasil Diubah');
        return redirect(route('member.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Membership $member)
    {
        $member->delete();
        session()->flash('success', 'Member Berhasil Dihapus');
        return redirect(route('member.index'));
    }
}
