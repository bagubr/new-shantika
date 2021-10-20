<?php

namespace App\Http\Controllers;

use App\Models\FleetClass;
use App\Models\FleetClassPrice;
use Illuminate\Http\Request;

class FleetClassPriceController extends Controller
{
    public function store(Request $request) {
        FleetClassPrice::create($request->toArray());

        return back()->with('success', 'Berhasil menambah data');
    }

    public function destroy($id) {
        FleetClassPrice::find($id)->delete();

        return back()->with('success', 'Berhasil menghapus data');
    }

    public function show($id) {
        
    } 
}