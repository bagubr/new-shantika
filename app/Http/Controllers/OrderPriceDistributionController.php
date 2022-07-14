<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\FleetDetail;
use App\Models\FleetRoute;
use App\Models\Order;
use App\Models\Area;
use App\Models\OrderDetail;
use App\Models\OrderPriceDistribution;
use App\Models\OutcomeDetail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SetoranExport;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;

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
        $area_id            = $request->area_id??Auth::user()->area_id;
        $areas              = Area::get();
        $fleet_details = FleetDetail::has('fleet_route')->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', '!=', $area_id);
            });
        })
        ->get();

        $fleet_routes = FleetRoute::when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', '!=', $area_id);
            });
        })
        ->get();

        $agencies = Agency::when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('city', function ($query) use ($area_id)
            {
                $query->where('area_id', $area_id);
            });
        })->get();

        $order_price_distributions  = OrderPriceDistribution::whereHas('order', function ($query)
        {
            $query->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED']);
        })
        ->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('order.fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', '!=', $area_id);
            });
        })
        ->when($fleet_detail_id, function ($query) use ($fleet_detail_id)
        {
            $query->whereHas('order', function ($q) use ($fleet_detail_id) {
                $q->whereHas('fleet_route', function ($sq) use ($fleet_detail_id) {
                    $sq->where('fleet_detail_id', $fleet_detail_id);
                });
            });
        })
        ->when($date_search, function ($query) use ($date_search)
        {
            $query->whereHas('order', function ($q) use ($date_search) {
                $q->whereDate('reserve_at', $date_search);
            });
        })
        ->when($agency_id, function ($query) use ($agency_id)
        {
            $query->whereHas('order', function ($q) use ($agency_id) {
                $q->where('departure_agency_id', $agency_id);
            });
        });


        $order_details = OrderDetail::whereHas('order', function ($query)
        {
            $query->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED']);
        })
        ->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('order.fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', '!=', $area_id);
            });
        })
        ->when($fleet_detail_id, function ($query) use ($fleet_detail_id)
        {
            $query->whereHas('order', function ($q) use ($fleet_detail_id) {
                $q->whereHas('fleet_route', function ($sq) use ($fleet_detail_id) {
                    $sq->where('fleet_detail_id', $fleet_detail_id);
                });
            });
        })
        ->when($date_search, function ($query) use ($date_search)
        {
            $query->whereHas('order', function ($q) use ($date_search) {
                $q->whereDate('reserve_at', $date_search);
            });
        })
        ->when($agency_id, function ($query) use ($agency_id)
        {
            $query->whereHas('order', function ($q) use ($agency_id) {
                $q->where('departure_agency_id', $agency_id);
            });
        })->get();

        $order = Order::whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED'])
        ->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', '!=', $area_id);
            });
        })
        ->when($fleet_detail_id, function ($query) use ($fleet_detail_id)
        {
            $query->whereHas('fleet_route', function ($q) use ($fleet_detail_id) {
                $q->where('fleet_detail_id', $fleet_detail_id);
            });
        })
        ->when($date_search, function ($query) use ($date_search)
        {
            $query->whereDate('reserve_at', $date_search);
        })
        ->when($agency_id, function ($query) use ($agency_id)
        {
            $query->where('departure_agency_id', $agency_id);
        })->get();


        $outcome_details = OutcomeDetail::when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('outcome.fleet_detail.fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', '!=', $area_id);
            });
        })
        ->when($fleet_detail_id, function ($query) use ($fleet_detail_id)
        {
            $query->whereHas('outcome', function ($q) use ($fleet_detail_id) {
                $q->where('fleet_detail_id', $fleet_detail_id);
            });
        })
        ->when($date_search, function ($query) use ($date_search)
        {
            $query->whereHas('outcome', function ($q) use ($date_search) {
                $q->whereDate('reported_at', $date_search);
            });
        })->get();


        $order_price_distributions  = $order_price_distributions->paginate(10);
        $total_deposit              = $order_price_distributions->pluck('total_deposit')->sum();
        $count_income               = $order_price_distributions->pluck('for_owner')->sum() + $order_price_distributions->pluck('for_food')->sum();
        $count_outcome              = $outcome_details->pluck('amount')->sum();
        $count_pendapatan_bersih    = $order_price_distributions->pluck('for_owner')->sum();
        $count_ticket               = $order->pluck('price')->sum();
        $count_seat                 = $order_details->count();
        return view('order_price_distribution.index', compact('count_ticket', 'order_price_distributions', 'fleet_details', 'outcome_details', 'fleet_routes', 'count_income', 'agencies', 'count_outcome', 'count_pendapatan_bersih', 'count_seat', 'total_deposit', 'agency_id', 'date_search', 'fleet_detail_id', 'areas', 'area_id'));
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
    public function update(Request $request, OrderPriceDistribution $order_price_distribution)
    {
        $data = $request->all();
        $data['deposited_at'] = date('Y-m-d');
        $order_price_distribution->update($data);
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
