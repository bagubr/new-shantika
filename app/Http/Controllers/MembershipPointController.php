<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\MembershipPoint;
use App\Services\MembershipService;
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
        ->orderBy('id', 'desc')
        ->get();
        return view('membership_point.index', compact('membership_points', 'membership'));
    }

    public function create(Request $request)
    {
        $membership_id = $request->membership_id;
        return view('membership_point.create', compact('membership_id'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $member = Membership::find($data['membership_id']);
        if($data['status'] == '1'){
            MembershipService::increment($member, $data['value'], 'Penambahan Point');
            session()->flash('success', 'Point Berhasil di tambahkan');
        }else{
            if($member->sum_point < 1){
                session()->flash('success', 'Point Tidak cukup');
            }else{
                MembershipService::decrement($member, $data['value'], 'Pengurangan Point');
                session()->flash('success', 'Point Berhasil di kurangi');
            }
        }
        return redirect(route('membership_point.index', ['membership_id' => $data['membership_id']]));
    }
}
