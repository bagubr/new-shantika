<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\FleetDetail;
use App\Models\FleetRoute;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPriceDistribution;
use App\Models\OutcomeDetail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SetoranExport;

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
        $agencies = Agency::get();
        $fleet_details = FleetDetail::has('fleet_route')->get();

        $order_price_distributions = OrderPriceDistribution::wherehas('order', function ($q) {
            $q->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED'])->orderBy('reserve_at', 'DESC');
        })->get();

        $outcome_details    = OutcomeDetail::all();
        $count_income       = OrderPriceDistribution::whereHas('order', function ($q) {
            $q->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED']);
        })->pluck('for_owner')->sum();
        $count_outcome      = OutcomeDetail::pluck('amount')->sum();
        $count_seat         = OrderDetail::whereHas('order', function ($q) {
            $q->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED']);
        })->get()->count();
        $count_ticket       = Order::whereIn('status', ['PAID', 'EXCHANGED', 'FINISHED'])->pluck('price')->sum();
        $count_pendapatan_bersih = $count_income - $count_outcome;
        return view('order_price_distribution.index', compact('count_ticket', 'order_price_distributions', 'count_seat', 'agencies', 'outcome_details', 'fleet_routes', 'count_income', 'count_outcome', 'count_pendapatan_bersih', 'fleet_details'));
    }
    public function search(Request $request)
    {
        $date_search        = $request->date_search;
        $fleet_detail_id    = $request->fleet_detail_id;
        $agency_id          = $request->agency_id;

        $fleet_details = FleetDetail::has('fleet_route')->get();

        $fleet_routes = FleetRoute::get();
        $agencies = Agency::all();

        $order_price_distributions  = OrderPriceDistribution::query();
        $outcome_details            = OutcomeDetail::query();
        $order_details              = OrderDetail::query();
        $tiket_price                = Order::query();

        if (!empty($fleet_detail_id)) {
            $order_price_distributions = $order_price_distributions->whereHas('order', function ($q) use ($fleet_detail_id) {
                $q->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED'])->whereHas('fleet_route', function ($sq) use ($fleet_detail_id) {
                    $sq->where('fleet_detail_id', $fleet_detail_id);
                });
            });
            $order_details      = $order_details->whereHas('order.fleet_route', function ($q) use ($fleet_detail_id) {
                $q->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED'])->where('fleet_detail_id', $fleet_detail_id);
            });
            $tiket_price        = $tiket_price->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED'])->whereHas('fleet_route', function ($q) use ($fleet_detail_id) {
                $q->where('fleet_detail_id', $fleet_detail_id);
            });
            $outcome_details    = $outcome_details->whereHas('outcome', function ($q) use ($fleet_detail_id) {
                $q->where('fleet_detail_id', $fleet_detail_id);
            });
        }
        if (!empty($date_search)) {
            $order_price_distributions  = $order_price_distributions->whereHas('order', function ($q) use ($date_search) {
                $q->whereDate('reserve_at', $date_search)->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED']);
            });
            $order_details              = $order_details->whereHas('order', function ($q) use ($date_search) {
                $q->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED'])->whereDate('reserve_at', $date_search);
            });
            $tiket_price                = $tiket_price->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED'])->whereDate('reserve_at', $date_search);
            $outcome_details            = $outcome_details->whereHas('outcome', function ($q) use ($date_search) {
                $q->whereDate('reported_at', $date_search);
            });
        }

        if (!empty($agency_id)) {
            $order_price_distributions  = $order_price_distributions->whereHas('order', function ($q) use ($agency_id) {
                $q->where('departure_agency_id', $agency_id)->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED']);
            });
            $order_details              = $order_details->whereHas('order', function ($q) use ($agency_id) {
                $q->where('departure_agency_id', $agency_id)->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED']);
            });
            $tiket_price                = $tiket_price->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED'])->where('departure_agency_id', $agency_id);
        }

        $test = $request->flash();
        $outcome_details            = $outcome_details->get();
        $order_price_distributions  = $order_price_distributions->get();

        $count_income               = $order_price_distributions->pluck('for_owner')->sum() + $order_price_distributions->pluck('for_food')->sum();
        $count_outcome              = $outcome_details->pluck('amount')->sum();
        $count_seat                 = $order_details->get()->count();
        $count_pendapatan_bersih    = $order_price_distributions->pluck('for_owner')->sum();
        $count_ticket               = $tiket_price->pluck('price')->sum();
        return view('order_price_distribution.index', compact('count_ticket', 'order_price_distributions', 'test', 'fleet_details', 'outcome_details', 'fleet_routes', 'count_income', 'agencies', 'count_outcome', 'count_pendapatan_bersih', 'count_seat'));
    }

    public function export(Request $request)
    {
        return Excel::download(new SetoranExport($request), date('dmYHis').'.xlsx');
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
