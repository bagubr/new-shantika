<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\CreateMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Imports\MembershipImport;
use App\Models\Agency;
use App\Models\Membership;
use App\Models\User;
use App\Repositories\AgencyRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member = Membership::all();
        $unique_member = $member->unique(['code_member']);
        $duplicate_member = $member->diff($unique_member)->count();
        $members = Membership::with('user')->orderBy('user_id', 'ASC')->paginate(10);
        return view('member.index', compact('members', 'duplicate_member'));
    }
    public function search(Request $request)
    {
        $member = Membership::all();
        $unique_member = $member->unique(['code_member']);
        $duplicate_member = $member->diff($unique_member)->count();
        $members = Membership::query();
        $name = $request->name;
        $code_member = $request->code_member;

        if (!empty($name)) {
            $members = $members->where('name', 'ilike', '%' . $name . '%');
        }
        if (!empty($code_member)) {
            $members = $members->orWhere('code_member', 'ilike', '%' . $code_member. '%');
        }

        $members = $members->orderBy('id', 'DESC')->paginate(10);
        if (!$members->isEmpty()) {
            session()->flash('success', 'Data Member Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
        return view('member.index', compact('members', 'code_member', 'name', 'duplicate_member'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $agencies = AgencyRepository::getOnlyIdName();
        return view('member.create', compact('agencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMemberRequest $request)
    {
        $data = $request->all();
        $member = Membership::orderBy('id', 'desc')->first()->code_member;
        $data['code_member'] = (int)$member + 1;
        $number = $request->phone;
        $country_code = '62';
        $isZero = substr($number, 0, 1);
        if ($isZero == '0') {
            $new_number = substr_replace($number, '+' . $country_code, 0, ($number[0] == '0'));
            $data['phone'] = $new_number;
        } else {
            $data['phone'] = $number;
        }
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
        $agencies = AgencyRepository::getOnlyIdName();
        return view('member.create', compact('member', 'agencies'));
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
        $number = $request->phone;
        $country_code = '62';
        $isZero = substr($number, 0, 1);
        if ($isZero == '0') {
            $new_number = substr_replace($number, '+' . $country_code, 0, ($number[0] == '0'));
            $data['phone'] = $new_number;
        } else {
            $data['phone'] = $number;
        }
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

    public function import(Request $request)
    {
        Excel::import(new MembershipImport(), $request->file('file'));
        return back()->with('success', 'Berhasil mengimport data');
    }
}
