<?php

namespace App\Exports;

use App\Models\Outcome;
use Maatwebsite\Excel\Concerns\FromCollection;

class OutcomeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Outcome::all();
    }
}
