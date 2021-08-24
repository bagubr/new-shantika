<?php

namespace App\Http\Controllers;

use App\Events\SendingNotification;
use App\Http\Resources\Layout\LayoutResource;
use App\Models\Area;
use App\Models\FleetRoute;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Repositories\LayoutRepository;
use App\Services\LayoutService;
use App\Utils\NotificationMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SketchController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::get();
        return view('sketch.index_1', [
            'areas'=>$areas
        ]);
    }

    public function getDeparturingOrders(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $area_id = $request->area_id;
        $orders = Order::whereIn('status', Order::STATUS_BOUGHT)
            ->with('fleet_route.fleet.fleetclass', 'fleet_route.route')
            ->with('fleet_route.fleet.layout')
            ->withCount(['order_detail'=>function($query) {
                $query->whereHas('order', function($subquery) {
                    $subquery->whereRaw('fleet_route_id = orders.fleet_route_id');
                });
            }])
            ->when($date, function ($query) use ($date) {
                $query->whereDate('reserve_at', $date);
            })
            ->when($area_id, function($query) use ($area_id) {
                $query->whereHas('fleet_route.route.departure_city', function($subquery) use ($area_id) {
                    $subquery->where('area_id', $area_id);
                });
            })
            ->distinct('fleet_route_id')
            ->get();
        
        return response([
            'orders'=>$orders
        ]);
    }

    public function getAvailibilityChairs(Request $request) {
        $fleet_route = FleetRoute::find($request->fleet_route_id);
        $layout = LayoutRepository::findByFleetRoute($fleet_route);
        $layout = LayoutService::getAvailibilityChairs($layout, $fleet_route, $request->date);
        
        $this->sendSuccessResponse([
            'data'=> new LayoutResource($layout),
            'fleet'=>$fleet_route->fleet?->load('fleetclass')
        ]);
    }

    public function store(Request $request) {
        $froms = $request->data['from_layout_chair_id'];
        $tos = $request->data['to_layout_chair_id'];

        DB::beginTransaction();
        $order = Order::where('id', $request->order_id)->first();
        $order->update([
            'fleet_route_id'=>$request->second_fleet_route_id
        ]);
        $order->refresh();

        $order_details = [];
        foreach($froms as $key => $value) {
            OrderDetail::where('order_id', $request->order_id)->where('layout_chair_id', $value['id'])->update([
                'layout_chair_id'=>$tos[$key]['id']
            ]);
            $detail = OrderDetail::where('order_id', $request->order_id)->where('layout_chair_id', $value['id'])->first();
            Log::info([$detail, $request->order_id, $value['id']]);
            $notification = Notification::build(
                "Perhatian! Kursi anda telah dipindah oleh sistem",
                NotificationMessage::changeChair($detail->order->fleet_route->fleet->name, $detail->chair->name),
                Notification::TYPE5,
                $order->id,
                $order->user_id
            );
            SendingNotification::dispatch($notification, $detail->order?->user?->fcm_token,true);
        }
        DB::commit();
        
        return response([$froms, $tos], 200);
    }
    
}
