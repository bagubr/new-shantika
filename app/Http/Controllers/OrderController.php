<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Route;
use App\Models\User;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderPriceDistributionRepository;
use App\Repositories\RoutesRepository;
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
        $orders = Order::orderBy('id', 'desc')->paginate(7);
        $routes = Route::all();
        $agent = ['AGENT', 'UMUM'];
        $status = ['PENDING', 'EXCHANGED', 'PAID', 'CANCELED', 'EXPIRED', 'WAITING_CONFIRMATION'];
        return view('order.index', compact('orders', 'routes', 'status', 'agent'));
    }
    public function search(Request $request)
    {
        $name_search = $request->name;
        $routes_search = $request->route_id;
        $status_search = $request->status;
        $status_agent = $request->agent;
        $code_order_search = $request->code_order;
        $date_from_search = $request->date_from;
        $date_from_to = $request->date_to;

        $routes = RoutesRepository::getIdName();
        $orders = Order::query();
        $status = ['PENDING', 'EXCHANGED', 'PAID', 'CANCELED', 'EXPIRED', 'WAITING_CONFIRMATION'];
        $agent = ['AGENT', 'UMUM'];

        if (!empty($routes_search)) {
            $orders = $orders->whereHas('fleet_route', function ($q) use ($routes_search) {
                $q->where('route_id', '=', $routes_search);
            });
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
        if (!empty($name_search)) {
            $orders = $orders->whereHas('user', function ($q) use ($name_search) {
                $q->where('name', 'like', '%' . $name_search . '%');
            });
        }
        if (!empty($code_order_search)) {
            $orders = $orders->where('code_order', 'like', '%' . $code_order_search . '%');
        }
        if (!empty($date_from_search)) {
            if (!empty($date_from_to)) {
                $orders = $orders->where('reserve_at', '>=', $date_from_search)->where('reserve_at', '<=', $date_from_to);
            }
            $orders = $orders->where('reserve_at', '>=', $date_from_search);
        }
        $test = $request->flash();
        $orders = $orders->orderBy('id', 'desc')->paginate(7);
        if (!$orders->isEmpty()) {
            session()->flash('success', 'Data Order Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
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
    public function show(Order $order)
    {
        $statuses = Order::status();
        $order_details = OrderDetailRepository::findById($order->id);
        $order_price_distributions = OrderPriceDistributionRepository::findById($order->id);
        return view('order.show', compact('order', 'order_details', 'order_price_distributions', 'statuses'));
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
        $order->payment()->delete();
        $order->order_detail()->delete();
        $order->delete();
        session()->flash('success', 'Pemesanan Berhasil Dihapus');
        return redirect(route('order.index'));
    }
}
