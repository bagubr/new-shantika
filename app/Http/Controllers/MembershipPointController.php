<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\MembershipPoint;
<<<<<<< HEAD
=======
use App\Services\MembershipService;
>>>>>>> rilisv1
use Illuminate\Http\Request;

class MembershipPointController extends Controller
{
    public function index(Request $request)
    {
        $membership_id = $request->membership_id;
<<<<<<< HEAD
=======
        $membership = Membership::findOrFail($membership_id);
>>>>>>> rilisv1

        $membership_points = MembershipPoint::when($membership_id, function ($query) use ($membership_id)
        {
            $query->where('membership_id', $membership_id);
        })
<<<<<<< HEAD
        ->get();
        return view('membership_point.index', compact('membership_points'));
    }

    public function show($id)
    {
        
=======
        ->orderBy('id', 'desc')
        ->get();
        return view('membership_point.index', compact('membership_points', 'membership'));
>>>>>>> rilisv1
    }

    public function create(Request $request)
    {
        $membership_id = $request->membership_id;
        return view('membership_point.create', compact('membership_id'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
<<<<<<< HEAD
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
=======
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
>>>>>>> rilisv1
        }
        return redirect(route('membership_point.index', ['membership_id' => $data['membership_id']]));
    }
}
