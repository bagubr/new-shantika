<?php

namespace App\Http\Controllers;

use App\Models\FleetRoute;
use App\Models\OrderPriceDistribution;
use App\Models\OutcomeDetail;
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
        $fleet_routes = FleetRoute::get();
        $order_price_distributions = OrderPriceDistribution::wherehas('order', function ($q) {
            $q->where('status', 'PAID')->orderBy('reserve_at', 'DESC');
        })->get();
        $outcome_details = OutcomeDetail::all();

        $count_income = OrderPriceDistribution::pluck('for_owner')->sum();
        $count_outcome = OutcomeDetail::pluck('amount')->sum();
        $count_pendapatan_bersih = $count_income - $count_outcome;
        return view('order_price_distribution.index', compact('order_price_distributions', 'outcome_details', 'fleet_routes', 'count_income', 'count_outcome', 'count_pendapatan_bersih'));
    }
    public function search(Request $request)
    {
        $date_search = $request->date_search;
        $fleet_route_search = $request->fleet_route_search;
        $fleet_routes = FleetRoute::get();

        $order_price_distributions = OrderPriceDistribution::query();
        $outcome_details = OutcomeDetail::query();
        if (!empty($date_search)) {
            $order_price_distributions = $order_price_distributions->whereHas('order', function ($q) use ($date_search) {
                $q->where('reserve_at', $date_search)->where('status', 'PAID');
            });
            $outcome_details = $outcome_details->whereHas('outcome', function ($q) use ($date_search) {
                $q->where('reported_at', $date_search);
            });
        }
        if (!empty($fleet_route_search)) {
            $order_price_distributions = $order_price_distributions->whereHas('order', function ($q) use ($fleet_route_search) {
                $q->where('fleet_route_id', $fleet_route_search)->where('status', 'PAID');
            });

            $outcome_details = $outcome_details->whereHas('outcome', function ($q) use ($fleet_route_search) {
                $q->where('fleet_route_id', $fleet_route_search);
            });
        }
        $test = $request->flash();
        $outcome_details = $outcome_details->get();
        $order_price_distributions = $order_price_distributions->get();
        $count_income = $order_price_distributions->pluck('for_owner')->sum();
        $count_outcome = $outcome_details->pluck('amount')->sum();
        $count_pendapatan_bersih = $count_income - $count_outcome;
        return view('order_price_distribution.index', compact('order_price_distributions', 'test', 'outcome_details', 'fleet_routes', 'count_income', 'count_outcome', 'count_pendapatan_bersih'));
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
