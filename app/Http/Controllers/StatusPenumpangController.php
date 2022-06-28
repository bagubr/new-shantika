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
    public function index(Request $request)
    {
<<<<<<< HEAD
        $area_id = $request->area_id;
        $areas = Area::get();
        $orders = Order::with('fleet_route.route')->when($area_id, function ($q) use ($area_id) {
            $q->whereHas('agency.city', function ($sq) use ($area_id) {
                $sq->where('area_id', $area_id);
            });
        })->paginate(10);
        $test = $request->flash();
=======
        $area_id = $request->area_id??'';
        $reserve_at = $request->reserve_at??'';
        $code_order = $request->code_order??'';
        $areas = Area::get();
        $orders = Order::with('fleet_route.route')
        ->when($area_id, function ($q) use ($area_id) {
            $q->whereHas('agency.city', function ($sq) use ($area_id) {
                $sq->where('area_id', $area_id);
            });
        })
        ->when($reserve_at, function ($q) use ($reserve_at) {
            $q->whereDate('reserve_at', $reserve_at);
        })
        ->when($code_order, function ($q) use ($code_order) {
            $q->where('code_order', $code_order);
        })
        ->paginate(10);
>>>>>>> rilisv1

        if (!$orders->isEmpty()) {
            session()->flash('success', 'Data Order Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
<<<<<<< HEAD
        return view('status_penumpang.index', compact('orders', 'areas', 'test'));
=======
        return view('status_penumpang.index', compact('orders', 'areas', 'reserve_at', 'area_id', 'code_order'));
>>>>>>> rilisv1
    }
    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
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
