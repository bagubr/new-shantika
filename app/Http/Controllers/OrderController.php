<?php

namespace App\Http\Controllers;

use App\Events\SendingNotification;
use App\Exports\OrdersExport;
use App\Http\Requests\Order\OrderCancelationRequest;
use App\Http\Requests\Order\UpdateOrderReserveAtRequest;
use App\Models\Agency;
use App\Models\FleetDetail;
use App\Models\FleetRoute;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Route;
use App\Models\SketchLog;
use App\Models\User;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderPriceDistributionRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RoutesRepository;
use App\Services\OrderPriceDistributionService;
use App\Services\OrderService;
use App\Utils\checkPassword;
use App\Utils\NotificationMessage;
use App\Utils\PriceTiket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name_search = $request->name;
        $name_non_search = $request->name_non_search;
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
        if (!empty($name_search)) {
            $orders = $orders->whereHas('user', function ($q) use ($name_search) {
                $q->where('name', 'ilike', '%' . $name_search . '%');
            });
        }
        if (!empty($name_non_search)) {
            $orders = $orders->whereHas('order_detail', function ($q) use ($name_non_search) {
                $q->where('name', 'ilike', '%' . $name_non_search . '%');
            });
        }
        if (!empty($status_agent)) {
            if ($status_agent == 'AGENT') {
                $orders = $orders->whereHas('user', function ($q) {
                    $q->has('agencies');
                });
            } elseif ($status_agent == 'UMUM') {
                if (empty($name_non_search)) {
                    $orders = $orders->whereDoesntHave('user');
                }else if(empty($name_search)){
                    $orders = $orders->whereDoesntHave('user.agencies');
                }
            }
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
        $orders = $orders->orderBy('id', 'desc')->paginate(10);
        if (!$orders->isEmpty()) {
            session()->flash('success', 'Data Order Berhasil Ditemukan');
        } else {
            session()->flash('error', 'Tidak Ada Data Ditemukan');
        }
        return view('order.index', compact('orders', 'routes', 'status', 'test', 'agent', 'fleet_details', 'agencies'));
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
        $data = $request->all();
        DB::beginTransaction();
        $order = new Order([
            'user_id'=>$request->user_id,
            'fleet_route_id'=>$request->fleet_route_id,
            'id_member'=>$request->id_member,
            'reserve_at'=>$request->reserve_at,
            'status'=>Order::STATUS3,
            'time_classification_id'=>$request->time_classification_id,
            'departure_agency_id'=>$request->departure_agency_id,
            'destination_agency_id'=>$request->destination_agency_id,
            'promo_id' => $request->promo_id,
            'note' => $request->note,
        ]);
        $request->merge([
            'total_price' => PriceTiket::priceTiket(FleetRoute::find($request->fleet_route_id), Agency::find($request->departure_agency_id), Agency::find($request->destination_agency_id), $request->reserve_at)
        ]);
        OrderService::create($order, $request);
        DB::commit();
        return response(['data' => $data, 'message' => 'Berhasil buat', 'code' => 1], 200);

    }

    public function showByCodeOrder($code_order)
    {
        $order = Order::with('order_detail')->where('code_order', $code_order)->first();
        if ($order == null) {
            return response()->json(array(
                'code'      =>  404,
                'message'   =>  'Data Tidak Ditemukan'
            ), 404);
        }
        return $this->sendSuccessResponse([
            'order' => $order
        ]);
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
        $data = $request->all();
        if(@$data['data_update']['reserve_at']){
            $data['data_update']['reserve_at'] = date('Y-m-d', strtotime($data['data_update']['reserve_at']));
        }
        DB::beginTransaction();
        try {
            $order = Order::find($id);
            $order->update($data['data_update']);
            $sketch_log = new SketchLogController();
            $request->merge([
                'admin_id' => Auth::user()->id,
                'order_id' => $id, 
                'from_date' => $data['sketch_log']['first_date'], 
                'to_date' => $data['sketch_log']['second_date'], 
                'from_fleet_route_id' => $data['sketch_log']['first_fleet_route_id'], 
                'to_fleet_route_id' => $data['sketch_log']['second_fleet_route_id'], 
                'from_layout_chair_id' => $data['sketch_log']['from_id'], 
                'to_layout_chair_id' => $data['sketch_log']['to_id'], 
                'from_time_classification_id' => $data['sketch_log']['first_time_classification_id'],
                'to_time_classification_id' => $data['sketch_log']['second_time_classification_id'],
                'type' => $data['sketch_log']['status']
            ]);
            $sketch_log->create($request);
            $order->refresh();
            DB::commit();
            return response(['data' => [], 'code' => 1], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response(['code' => 0, 'message' => 'Something Wrong !!, Check your connection'], 200);
        }

    }
    
    public function update_price($id)
    {
        $order = Order::with('order_detail')->find($id);
        $total_chairs = $order->order_detail->count() + 1;
        $data['order_price'] = $order->price;
        $data['total_chairs'] = $total_chairs;
        $data['ticket_price'] = $order->price / $total_chairs;
        $data['price'] = $data['ticket_price'] * $order->order_detail->count();
        DB::beginTransaction();
        try {
            $order->update($data);
            $order->refresh();
            $price_food = $order->distribution->for_food / $total_chairs;
            $total_travel = $order->distribution->total_travel / $total_chairs;
            $total_member = $order->distribution->total_member / $total_chairs;
            $total_price = OrderPriceDistributionService::calculateDistribution($order, $order->order_detail, $data['price'], $price_food, $total_travel, $total_member);
            $order->distribution->update([
                'for_food' => $total_price['for_food'],
                'for_travel' => $total_price['for_travel'],
                'for_member' => $total_price['for_member'],
                'for_agent' => $total_price['for_agent'],
                'for_owner' => $total_price['for_owner'],
                'ticket_only' => $total_price['ticket_only'],
                'for_owner_with_food' => $total_price['for_owner_with_food'],
                'for_owner_gross' => $total_price['for_owner_gross'],
                'total_deposit' => $total_price['total_deposit'],
                'ticket_price' => $total_price['ticket_price'],
            ]);
            DB::commit();
            return response(['data_update_price' => $order, 'price' => $data, 'code' => 1], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response(['code' => 0, 'price' => $data, 'message' => 'Gagal Update Harga !!!'], 200);
        }

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
            SendingNotification::dispatch($notification, $order->user?->fcm_token, true);
            session()->flash('success', 'Jadwal Berhasil Diubah');
        } else {
            session()->flash('error', 'Password Anda Tidak Sama');
        }
        return back();
    }
    public function cancelation(OrderCancelationRequest $request, OrderDetail $order_detail)
    {
        $order_detail->load('order');
        $order = $order_detail->order;
        $data               = $request->all();
        $data['status']     = 'CANCELED';
        $validate = CheckPassword::checkPassword($data['password']);
        if($validate){
            return $validate;
        }
        DB::beginTransaction();
        $message = NotificationMessage::orderCanceled($order_detail->order->fleet_route->fleet_detail->fleet->name, $request->cancelation_reason);
        $notification = Notification::build($message[0], $message[1], Notification::TYPE1, $order_detail->order_id, $order_detail->order->user_id);
        if (!empty($request->is_all)) {
            $order_detail->order()->update([
                'status' => Order::STATUS4,
                'cancelation_reason' => $request->cancelation_reason
            ]);
            SketchLog::create([
                'admin_id' => Auth::user()->id,
                'order_id' => $order_detail->order_id,
                'from_date' => $order->reserve_at,
                'to_date' => $order->reserve_at,
                'from_fleet_route_id' => $order->fleet_route_id,
                'to_fleet_route_id' => $order->fleet_route_id,
                'from_layout_chair_id' => $order_detail->layout_chair_id,
                'to_layout_chair_id' => $order_detail->layout_chair_id,
                'from_time_classification_id' => $order->time_classification_id,
                'to_time_classification_id' => $order->time_classification_id,
                'type' => SketchLog::TYPE2
            ]);

            SendingNotification::dispatch($notification, $order_detail->order?->user?->fcm_token, true);

            session()->flash('success', 'Berhasil menghapus order');
            return response([
                'code' => 1
            ], 200);
        }

        if (count($order_detail->order->order_detail) > 1) {
            $order_detail->order()->update([
                'cancelation_reason' => $request->cancelation_reason
            ]);
            $is_reverting = OrderService::revertPrice($order_detail, $order);
            if (!$is_reverting) return response([
                'code' => 0
            ], 500);
            // $order_detail->delete();
            SketchLog::create([
                'admin_id' => Auth::user()->id,
                'order_id' => $order_detail->order_id,
                'from_date' => $order->reserve_at,
                'to_date' => $order->reserve_at,
                'from_fleet_route_id' => $order->fleet_route_id,
                'to_fleet_route_id' => $order->fleet_route_id,
                'from_layout_chair_id' => $order_detail->layout_chair_id,
                'to_layout_chair_id' => $order_detail->layout_chair_id,
                'from_time_classification_id' => $order->time_classification_id,
                'to_time_classification_id' => $order->time_classification_id,
                'type' => SketchLog::TYPE2
            ]);
        } else {
            $order_detail->order()->update([
                'status' => Order::STATUS4,
                'cancelation_reason' => $request->cancelation_reason
            ]);
            SketchLog::create([
                'admin_id' => Auth::user()->id,
                'order_id' => $order_detail->order_id,
                'from_date' => $order->reserve_at,
                'to_date' => $order->reserve_at,
                'from_fleet_route_id' => $order->fleet_route_id,
                'to_fleet_route_id' => $order->fleet_route_id,
                'from_layout_chair_id' => $order_detail->layout_chair_id,
                'to_layout_chair_id' => $order_detail->layout_chair_id,
                'from_time_classification_id' => $order->time_classification_id,
                'to_time_classification_id' => $order->time_classification_id,
                'type' => SketchLog::TYPE2
            ]);
        }

        DB::commit();
        SendingNotification::dispatch($notification, $order_detail->order?->user?->fcm_token, true);

        session()->flash('success', 'Berhasil menghapus order');
        return response([
            'code' => 1
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
