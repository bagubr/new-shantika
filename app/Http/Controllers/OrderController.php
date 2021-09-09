<?php

namespace App\Http\Controllers;

use App\Events\SendingNotification;
use App\Http\Requests\Order\OrderCancelationRequest;
use App\Http\Requests\Order\UpdateOrderReserveAtRequest;
use App\Models\Agency;
use App\Models\FleetDetail;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Route;
use App\Models\User;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderPriceDistributionRepository;
use App\Repositories\RoutesRepository;
use App\Services\OrderService;
use App\Utils\NotificationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        $fleet_details = FleetDetail::has('fleet_route')->get();
        $agent = ['AGENT', 'UMUM'];
        $agencies = Agency::orderBy('name', 'asc')->get();
        $status = ['PENDING', 'EXCHANGED', 'PAID', 'CANCELED', 'EXPIRED', 'WAITING_CONFIRMATION'];
        return view('order.index', compact('orders', 'routes', 'status', 'agent', 'fleet_details', 'agencies'));
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
        $fleet_detail_id = $request->fleet_detail_id;
        $agency_id = $request->agency_id;

        $routes = RoutesRepository::getIdName();
        $orders = Order::query();
        $fleet_details = FleetDetail::has('fleet_route')->get();
        $agencies = Agency::orderBy('name', 'asc')->get();
        $status = ['PENDING', 'EXCHANGED', 'PAID', 'CANCELED', 'EXPIRED', 'WAITING_CONFIRMATION'];
        $agent = ['AGENT', 'UMUM'];

        if (!empty($agency_id)) {
            $orders = $orders->where('departure_agency_id', $agency_id);
        }
        if (!empty($fleet_detail_id)) {
            $orders = $orders->whereHas('fleet_route', function ($q) use ($fleet_detail_id) {
                $q->where('fleet_detail_id', $fleet_detail_id);
            });
        }
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
                })->orWhereDoesntHave('user');
            }
        }
        if (!empty($name_search)) {
            $orders = $orders->whereHas('user', function ($q) use ($name_search) {
                $q->where('name', 'ilike', '%' . $name_search . '%');
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
        $orders = $orders->orderBy('id', 'desc')->get();
        if (!$orders->isEmpty()) {
            session()->flash('success', 'Data Order Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
        return view('order.index', compact('orders', 'routes', 'status', 'test', 'agent', 'fleet_details', 'agencies'));
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

    public function update_jadwal(UpdateOrderReserveAtRequest $request, Order $order)
    {
        $data = $request->all();
        $data['password'] = $request->password;
        $hashed = Auth::user()->password;
        if (Hash::check($data['password'], $hashed)) {
            $order->update($data);
            $order->refresh();
            $message = NotificationMessage::scheduleChanged($order->fleet_route?->fleet_detail?->fleet?->name, date('d-m-Y', strtotime($order->created_at)));
            $notification = Notification::build($message[0], $message[1], Notification::TYPE1, $order->id, $order->user?->id);
            SendingNotification::dispatch($notification, $order->user?->fcm_token,true);
            session()->flash('success', 'Jadwal Berhasil Diubah');
        } else {
            session()->flash('error', 'Password Anda Tidak Sama');
        }
        return back();
    }
    public function cancelation(OrderCancelationRequest $request, OrderDetail $order_detail)
    {
        $data               = $request->all();
        $data['status']     = 'CANCELED';
        $hashed = Auth::user()->password;
        if (!Hash::check($data['password'], $hashed)) {
            session()->flash('error', 'Password anda tidak sama');
            return response([
                'code'=>0
            ]);
        }
        DB::beginTransaction();
        if(count($order_detail->order->order_detail) > 1) {
            $order_detail->delete();
            // OrderService::revertPrice($order_detail);
        } else {
            $order_detail->order->update([
                'status'=>Order::STATUS4,
            ]);
        }
        session()->flash('success', 'Berhasil menghapus order');
        return response([
            'code'=>1
        ], 200);
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
