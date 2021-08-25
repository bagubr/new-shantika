<?php

namespace App\Exports;

use App\Models\Outcome;
use App\Models\OutcomeDetail;
use App\Models\Order;
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
    public function __construct(int $id)
    {
        $this->id  = $id;
    }
    public function view(): View
    {
        $outcome =  Outcome::with('outcome_detail')->find($this->id);
        $orders = Order::whereIn('id', json_decode($outcome->order_price_distribution_id))->get();
        return view('excel_export.outcome', [
            'outcome' => $outcome,
            'orders' => $orders,
        ]);
    }
}
