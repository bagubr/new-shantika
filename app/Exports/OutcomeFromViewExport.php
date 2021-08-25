<?php

namespace App\Exports;

use App\Models\Outcome;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OutcomeFromViewExport implements FromView
{
    protected $id;
    
    public function __construct(int $id)
    {
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $outcome = Outcome::with('outcome_detail')->find($this->id);
        $orders = Order::whereIn('id', json_decode($outcome->order_price_distribution_id))->get();
        return view('outcome.show', compact('outcome', 'orders'));
    }
}
