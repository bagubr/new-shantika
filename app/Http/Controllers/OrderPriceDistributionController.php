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
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Auth;
>>>>>>> rilisv1

class OrderPriceDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date_search        = $request->date_search;
        $fleet_detail_id    = $request->fleet_detail_id;
        $agency_id          = $request->agency_id;
<<<<<<< HEAD

        $fleet_details = FleetDetail::has('fleet_route')->get();

        $fleet_routes = FleetRoute::get();
        $agencies = Agency::all();
=======
        $area_id            = Auth::user()->area_id;
        $fleet_details = FleetDetail::has('fleet_route')
        ->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        })
        ->get();

        $fleet_routes = FleetRoute::
        when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        })
        ->get();
        $agencies = Agency::
        when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        })->get();
>>>>>>> rilisv1

        $order_price_distributions  = OrderPriceDistribution::query();
        $outcome_details            = OutcomeDetail::query();
        $order_details              = OrderDetail::query();
        $tiket_price                = Order::query();
<<<<<<< HEAD
=======
        
        $order_price_distributions->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('order.fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        });
        $outcome_details->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('outcome.fleet_detail.fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        });
        $order_details->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('order.fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        });
        $tiket_price->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        });
>>>>>>> rilisv1

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
        $order_price_distributions  = $order_price_distributions->paginate(10);

        $total_deposit              = $order_price_distributions->pluck('total_deposit')->sum();
        $count_income               = $order_price_distributions->pluck('for_owner')->sum() + $order_price_distributions->pluck('for_food')->sum();
        $count_outcome              = $outcome_details->pluck('amount')->sum();
        $count_seat                 = $order_details->get()->count();
        $count_pendapatan_bersih    = $order_price_distributions->pluck('for_owner')->sum();
        $count_ticket               = $tiket_price->pluck('price')->sum();
        return view('order_price_distribution.index', compact('count_ticket', 'order_price_distributions', 'test', 'fleet_details', 'outcome_details', 'fleet_routes', 'count_income', 'agencies', 'count_outcome', 'count_pendapatan_bersih', 'count_seat', 'total_deposit'));
    }
    public function export(Request $request)
    {
        parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
        // $fleet_detail_id = $queries['fleet_detail_id'];
        // dd($queries['fleet_detail_id']);
        return Excel::download(new SetoranExport($request), 'setoran_' . date('dmYHis') . '.xlsx');
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
<<<<<<< HEAD
    public function update(OrderPriceDistribution $order_price_distribution)
    {
        $order_price_distribution->update([
            'deposited_at' => date('Y-m-d'),
        ]);
=======
    public function update(Request $request, OrderPriceDistribution $order_price_distribution)
    {
        $data = $request->all();
        $data['deposited_at'] = date('Y-m-d');
        $order_price_distribution->update($data);
>>>>>>> rilisv1
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
