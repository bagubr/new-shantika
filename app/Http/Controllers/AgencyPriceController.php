<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyPrice;
use App\Models\RoutePrice;
use Illuminate\Http\Request;

class AgencyPriceController extends Controller
{

    public function store(Request $request) {
        $request->validate([
            'agency_id'=>'required',
            'agency_price'=>'required|numeric',
            'start_at_agency'=>'required|date'
        ]);
        $data = [
            'agency_id' => $request->agency_id,
            'price' => $request->agency_price,
            'start_at' => $request->start_at_agency,
        ];
        AgencyPrice::create($data);

        return back()->with('success', 'Berhasil menambahkan harga');
    }

    public function storeRoute(Request $request) {
        $request->validate([
            'agency_id'=>'required',
            'route_price'=>'required|numeric',
            'start_at_route'=>'required|date'
        ]);
        $data = [
            'agency_id' => $request->agency_id,
            'price' => $request->route_price,
            'start_at' => $request->start_at_route,
        ];
        RoutePrice::create($data);

        return back()->with('success', 'Berhasil menambahkan harga');
    }

    public function show(Request $request, $id) {
        $agency = Agency::find($id);
        $agency_price = AgencyPrice::where('agency_id', $agency->id)->get();
        $route_price = RoutePrice::where('agency_id', $agency->id)->get();
        return view('agency.change-price', compact('agency_price', 'agency', 'route_price'));
    }

    public function destroy($id) {
        AgencyPrice::find($id)->delete();
        return back()->with('success', 'Berhasil menghapus harga agen');
    }

    public function destroyRoute($id) {
        RoutePrice::find($id)->delete();
        return back()->with('success', 'Berhasil menghapus harga rute');
    }
}
