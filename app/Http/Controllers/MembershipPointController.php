<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\MembershipPoint;
use App\Models\Setting;
use App\Repositories\MembershipRepository;
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
        $member = Membership::find($data['membership_id']);
        if($data['status'] == 'redeem'){
            MembershipRepository::incrementPoint([
                'membership_id' => $member->id,
                'value' => $data['value'],
                'message' => 'Pengurangan Point'
            ]);
            session()->flash('success', 'Point Berhasil di kurangi');
        }else{
            MembershipRepository::decrementPoint([
                'membership_id' => $member->id,
                'value' => $data['value'],
                'message' => 'Penambahan Point'
            ]);
            session()->flash('success', 'Point Berhasil di tambahkan');
        }
        return redirect(route('membership_point.index', ['membership_id' => $data['membership_id']]));
    }
}
