<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\MembershipPoint;
use Illuminate\Http\Request;

class MembershipPointController extends Controller
{
    public function index(Request $request)
    {
        $membership_id = $request->membership_id;
        $membership = Membership::findOrFail($membership_id);

        $membership_points = MembershipPoint::when($membership_id, function ($query) use ($membership_id)
        {
            $query->where('membership_id', $membership_id);
        })
        ->where('status', '!=', 'create')
        ->get();
        return view('membership_point.index', compact('membership_points', 'membership'));
    }

    public function show($id)
    {
        
    }

    public function create(Request $request)
    {
        $membership_id = $request->membership_id;
        return view('membership_point.create', compact('membership_id'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        MembershipPoint::create($data);
        $member = Membership::find($data['membership_id']);
        if($data['status'] == 'redeem'){
            $member->update([
                'sum_point' => $member->sum_point - $data['value']
            ]);
            session()->flash('success', 'Point Berhasil di kurangi');
        }else{
            $member->update([
                'sum_point' => $member->sum_point + $data['value']
            ]);
            session()->flash('success', 'Point Berhasil di tambahkan');
        }
        return redirect(route('membership_point.index', ['membership_id' => $data['membership_id']]));
    }
}
