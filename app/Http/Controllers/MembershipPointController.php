<?php

namespace App\Http\Controllers;

use App\Models\MembershipPoint;
use Illuminate\Http\Request;

class MembershipPointController extends Controller
{
    public function index(Request $request)
    {
        $membership_id = $request->membership_id;

        $membership_point = MembershipPoint::when($membership_id, function ($query) use ($membership_id)
        {
            $query->where('membership_id', $membership_id);
        })
        ->get();

        return view('membershippoint.index', compact('membership_point'));
    }

    public function show($id)
    {
        
    }

    public function create()
    {
        return view('membership_point');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // $data['note'] = 'Tambah admin';
        MembershipPoint::create($data);
        session()->flash('success', 'Point Berhasil di tambahkan');
        return redirect(route('membership_point.index'));
    }
}
