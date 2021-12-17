<?php

namespace App\Http\Controllers;

use App\Models\AgencyPrice;
use Illuminate\Http\Request;

class AgencyPriceController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'agency_id'=>'required',
            'price'=>'required|numeric',
            'start_at'=>'required|date'
        ]);
        AgencyPrice::create($request->toArray());

        return back()->with('success', 'Berhasil menambahkan harga');
    }

    public function show(Request $request, $id) {
            
    }

    public function destroy($id) {
        AgencyPrice::find($id)->delete();

        return back()->with('success', 'Berhasil menghapus harga agen');
    }
}
