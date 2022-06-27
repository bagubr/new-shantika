<?php

namespace App\Http\Controllers;

use App\Exports\MembershipHistoryExport;
use App\Models\Membership;
use App\Models\MembershipHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MembershipHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $query = MembershipHistory::when($request->start_date && $request->end_date, function ($query) use ($request)
        {
            $query->whereDate('created_at', '>=', $request->start_date);
            $query->whereDate('created_at', '<=', $request->end_date);
        });
        
        $membership_histories = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $total = $query->count();
        $nominal = $query->has('order.distribution')->get()->sum('order.distribution.for_member');
        if($request->export){
            return Excel::download(new MembershipHistoryExport($request), 'membership_histories.xlsx');
        }
        return view('membership_history.index', compact('membership_histories', 'start_date', 'total', 'end_date', 'nominal'));
    }
}
