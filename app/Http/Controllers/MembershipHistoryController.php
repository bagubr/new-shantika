<?php

namespace App\Http\Controllers;

use App\Exports\MembershipHistoryExport;
use App\Models\MembershipHistory;
use Illuminate\Http\Request;
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
        $created_at = $request->created_at;
        $membership_histories = MembershipHistory::when($request->created_at, function ($query) use ($request)
        {
            $query->whereDate('created_at', $request->created_at);
        })->paginate(10)->appends(request()->query());
        if($request->export){
            return Excel::download(new MembershipHistoryExport($request), 'membership_histories.xlsx');
        }
        return view('membership_history.index', compact('membership_histories', 'created_at'));
    }
}
