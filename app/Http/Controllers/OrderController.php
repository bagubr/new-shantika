<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Route;
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
        $routes = Route::all();
        $orders = Order::query();
        $status = ['PENDING', 'EXCHANGED', 'PAID', 'CANCELED', 'EXPIRED'];
        if (!empty($routes_search)) {
            $orders = $orders->where('route_id', $routes_search);
        }
        if (!empty($status_search)) {
            $orders = $orders->where('status', $status_search);
        }
        $test = $request->flash();
        $orders = $orders->get();
        // return redirect(route('order.index', compact('orders', 'routes', 'status')))->withInput();
        return view('order.index', compact('orders', 'routes', 'status', 'test'));
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
