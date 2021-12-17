<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\FoodRedeemHistory;
use App\Models\RestaurantAdmin;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodRedeemHistoryController extends Controller
{
    public function store(Request $request) {
        $data = $request->validate([
            'code_order'=>'required'
        ]);
        $order = OrderRepository::findByCodeOrder($data['code_order']);
        $redeems = FoodRedeemHistory::where('order_id', $order->id)->get();
        $ticket_count = count($order->order_detail);
        $auth = Auth::user();
        $restaurant_admin = @RestaurantAdmin::where('admin_id', $auth->id)->first()->restaurant_id;
        if(empty($order)) $this->sendFailedResponse([], 'Kode order tidak ditemukan');
        if($redeems >= $ticket_count) $this->sendFailedResponse([], 'Anda sudah meredeem kode order ini sebanyak jumlah tiket anda'); 
        if(empty($restaurant_admin)) $this->sendFailedResponse([], 'ID Anda tidak ditemukan');
        
        $history = FoodRedeemHistory::create([
            'order_id'=>$order->id,
            'restaurant_id'=>$restaurant_admin->restaurant_id
        ]);

        return $this->sendSuccessResponse([
            'history'=>$history   
        ]);
    }

    public function index(Request $request) {
        $histories = FoodRedeemHistory::when($request->restaurant_id, function($query) use ($request) {
                $query->where('restaurant_id', $request->restaurant_id);
            })
            ->when($request->area_id, function($query) use ($request) {
                $query->whereHas('order.fleet_route_id.route.checkpoints.agency.city', function($sq) use ($request) {
                    $sq->where('area_id', $request->area_id);
                });
            })
            ->when($request->fleet_id, function($query) use ($request) {
                $query->whereHas('order.fleet_route_id.fleet_detail', function($sq) use ($request) {
                    $sq->where('fleet_id', $request->fleet_id);
                });
            })
            ->when($request->fleet_route_id, function($query) use ($request) {
                $query->whereHas('order', function($sq) use ($request) {
                    $sq->where('fleet_route_id', $request->fleet_route_id);
                });
            })
            ->get();
    }

    public function show(Request $request, $id) {
        $history = FoodRedeemHistory::with([
            'order.fleet_route_id.route.checkpoints.agency.city',
            'order.fleet_route_id.fleet_detail.fleet.fleetclass'
        ])->find($id);
    }

    
}
