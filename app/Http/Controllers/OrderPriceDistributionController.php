<?php

namespace App\Http\Controllers;

use App\Models\OrderPriceDistribution;
use Illuminate\Http\Request;

class OrderPriceDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order_price_distributions = OrderPriceDistribution::wherehas('order', function ($q) {
            $q->orderBy('reserve_at', 'DESC');
        })->get();
        return view('order_price_distribution.index', compact('order_price_distributions'));
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
    public function edit(OrderPriceDistribution $order_price_distribution)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderPriceDistribution $order_price_distribution)
    {
        $order_price_distribution->update([
            'deposited_at' => date('Y-m-d'),
        ]);
        session()->flash('success', 'Deposit Berhasil Diupdate');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderPriceDistribution $order_price_distribution)
    {
        $order_price_distribution->delete();
        session()->flash('success', 'Setoran Berhasil Dihapus');
        return back();
    }
}
