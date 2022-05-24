<?php

namespace App\Exports;

use App\Models\MembershipHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MembershipHistoryExport implements FromView, ShouldAutoSize
{

    public function __construct(Request $request)
    {
        $this->request  = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $request = $this->request;
        $membership_histories = MembershipHistory::when($request->created_at, function ($query) use ($request)
        {
            $query->whereDate('created_at', $request->created_at);
        })->get();
        return view('excel_export.membership_histories', [
            'membership_histories' => $membership_histories,
        ]);
    }
}
