<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Models\Area;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StatusPenumpangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::get();
        $orders = Order::all();
        return view('status_penumpang.index', compact('orders', 'areas'));
    }
    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xslx');
    }
    public function search(Request $request)
    {
        $area_id = $request->area_id;
        $areas = Area::get();
        $orders = Order::with('fleet_route.route')->when($area_id, function ($q) use ($area_id) {
            $q->whereHas('fleet_route.route', function ($z) use ($area_id) {
                $z->whereHas('departure_city.area', function ($y) use ($area_id) {
                    $y->where('id', $area_id);
                });
            });
        })->get();
        if (!$orders->isEmpty()) {
            session()->flash('success', 'Data Order Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
        return view('status_penumpang.index', compact('orders', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
