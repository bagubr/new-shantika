<?php

namespace App\Exports;

use App\Models\Outcome;
use App\Models\OutcomeDetail;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

// class OutcomeExport implements FromCollection
// {
//     /**
//      * @return \Illuminate\Support\Collection
//      */
//     public function collection()
//     {
//         return Outcome::with('outcome_detail')->get();
//     }
// }
class OutcomeExport implements FromView
{
    public function view(): View
    {
        return view('excel_export.outcome', [
            'outcomes' => OutcomeDetail::all()
        ]);
    }
}
