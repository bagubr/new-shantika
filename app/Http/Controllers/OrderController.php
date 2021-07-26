<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        $routes = Route::all();
        $status = ['PENDING', 'EXCHANGED', 'PAID', 'CANCELED', 'EXPIRED'];
        return view('order.index', compact('orders', 'routes', 'status'));
    }
    public function search(Request $request)
    {
        $routes_search = $request->route_id;
        $status_search = $request->status;
        $status_agent = $request->agent;
        $routes = Route::all();
        $orders = Order::query();
        $status = ['PENDING', 'EXCHANGED', 'PAID', 'CANCELED', 'EXPIRED'];
        $agent = ['AGENT', 'UMUM'];
        if (!empty($routes_search)) {
            $orders = $orders->where('route_id', $routes_search);
        }
        if (!empty($status_search)) {
            $orders = $orders->where('status', $status_search);
        }
        if (!empty($status_agent)) {
            if ($status_agent == 'AGENT') {
                $orders = $orders->whereHas('user', function ($q) {
                    $q->has('agencies');
                });
            } elseif ($status_agent == 'UMUM') {
                $orders = $orders->whereHas('user', function ($y) {
                    $y->doesntHave('agencies');
                });
            }
        }
        $test = $request->flash();
        $orders = $orders->get();
        return view('order.index', compact('orders', 'routes', 'status', 'test', 'agent'));
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
    public function destroy(Order $order)
    {
        $order->order_detail()->delete();
        $order->delete();
        session()->flash('success', 'Pemesanan Berhasil Dihapus');
        return redirect(route('order.index'));
    }
}
