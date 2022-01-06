<?php

namespace App\Http\Controllers;

use App\Events\SendingNotification;
use App\Events\SendingNotificationToAdmin;
use App\Http\Resources\Layout\LayoutResource;
use App\Models\Area;
use App\Models\FleetRoute;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Agency;
use App\Repositories\LayoutRepository;
use App\Services\LayoutService;
use App\Utils\NotificationMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LangsirExport;
use App\Models\AdminNotification;
use App\Models\SketchLog;
use App\Models\TimeClassification;
use App\Repositories\BookingRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use PDF;
use Throwable;

class SketchController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::get();
        $time_classifications = TimeClassification::all();
        return view('sketch.index_1', [
            'areas' => $areas,
            'time_classifications' => $time_classifications
        ]);
    }

    public function getDeparturingOrders(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $area_id = $request->area_id;
        $time_classification_id = $request->time_classification_id;
        $orders = Order::select('*')
            ->whereIn('status', Order::STATUS_BOUGHT)
            ->with('fleet_route.fleet_detail.fleet.fleetclass', 'fleet_route.route')
            ->with('fleet_route.fleet_detail.fleet.layout', 'time_classification')
            ->when($date, function ($query) use ($date) {   
                $query->whereDate('reserve_at', $date);
            })
            ->when($area_id, function ($query) use ($area_id) {
                $query->whereHas('fleet_route.route.checkpoints', function ($subquery) use ($area_id) {
                    $subquery->whereHas('agency.city', function ($subsubquery) use ($area_id) {
                        $subsubquery->where('area_id', '!=', $area_id);
                    });
                });
            })
            ->when($request->time_classification_id, function ($query) use ($time_classification_id) {
                $query->where('time_classification_id', $time_classification_id);
            })
            ->distinct(['fleet_route_id', 'time_classification_id'])
            ->get();
        foreach ($orders as $order) {
            $order->order_detail_count = count(OrderDetailRepository::findForPriceDistributionByUserAndDateAndFleet(null, $order->reserve_at, $order->fleet_route?->fleet_detail?->fleet_id, $order->time_classification_id));
        }
        return response([
            'orders' => $orders
        ]);
    }

    public function getAvailibilityChairs(Request $request)
    {
        $fleet_route = FleetRoute::find($request->fleet_route_id);
        $layout = LayoutRepository::findByFleetRoute($fleet_route);
        $layout = LayoutService::getAvailibilityChairsDetail($layout, $fleet_route, $request->date, $request->time_classification_id);
        $this->sendSuccessResponse([
            'data' => new LayoutResource($layout),
            'fleet' => $fleet_route->fleet_detail?->fleet?->load('fleetclass')
        ]);
    }

    public function store(Request $request)
    {
        $froms = $request->data['from_layout_chair_id'];
        $tos = $request->data['to_layout_chair_id'];

        DB::beginTransaction();
        try {
            $detail = [];
            foreach ($froms as $key => $value) {
                $detail[$key] = OrderDetail::with('order')->whereHas('order', function ($query) use ($request) {
                    $query->whereDate('reserve_at', date('Y-m-d', strtotime($request->data['from_date'])))
                        ->where('fleet_route_id', $request['first_fleet_route_id'])
                        ->where('time_classification_id', $request->data['from_time_classification_id']);
                })->where('layout_chair_id', $value['id'])->first();

                $is_exist = OrderRepository::isOrderUnavailable($request['second_fleet_route_id'], date('Y-m-d', strtotime($request->data['to_date'])), $tos[$key]['id'], $request->data['to_time_classification_id']);
                $is_exist = $is_exist || BookingRepository::isBooked($request['second_fleet_route_id'], $detail[$key]->order->user_id, $tos[$key]['id'], date('Y-m-d', strtotime($request->data['to_date'])), $request->data['to_time_classification_id']);
                if ($is_exist) throw new Exception('Kursi telah diorder');

                $detail[$key]->update([
                    'layout_chair_id' => $tos[$key]['id']
                ]);
            }
            foreach ($froms as $key => $value) {
                $detail[$key]->order()->update([
                    'fleet_route_id' => $request['second_fleet_route_id'],
                    'reserve_at' => date('Y-m-d H:i:s', strtotime($request->data['to_date'])),
                    'time_classification_id' => $request->data['to_time_classification_id']
                ]);
                SketchLog::create([
                    'admin_id' => Auth::user()->id,
                    'order_id' => $detail[$key]->order_id,
                    'from_date' => $detail[$key]->order->reserve_at,
                    'to_date' => date('Y-m-d H:i:s', strtotime($request->data['to_date'])),
                    'from_fleet_route_id' => $request['first_fleet_route_id'],
                    'to_fleet_route_id' => $request['second_fleet_route_id'],
                    'from_layout_chair_id' => $value['id'],
                    'to_layout_chair_id' => $tos[$key]['id'],
                    'from_time_classification_id' => $request->data['from_time_classification_id'],
                    'to_time_classification_id' => $request->data['to_time_classification_id'],
                    'type' => SketchLog::TYPE1
                ]);
                $detail[$key]->refresh();
                $notification = Notification::build(
                    NotificationMessage::changeChair($detail[$key]->order?->fleet_route?->fleet_detail?->fleet?->name, $detail[$key]->chair?->name, date('d-M-Y', strtotime($request->data['to_date'])))[0],
                    NotificationMessage::changeChair($detail[$key]->order?->fleet_route?->fleet_detail?->fleet->name, $detail[$key]->chair?->name, date('d-M-Y', strtotime($request->data['to_date'])))[1],
                    Notification::TYPE5,
                    $detail[$key]->order->id,
                    $detail[$key]->order->user_id
                );
                SendingNotification::dispatch($notification, $detail[$key]->order?->user?->fcm_token, true);
                AdminNotification::create([
                    'title' => $notification->title,
                    'body' => $notification->body,
                    'type' => $notification->type,
                    "reference_id" => $notification->reference_id,
                ]);
            }
        } catch (Throwable $t) {
            session()->flash('error', 'Kursi penumpang gagal diubah');
            Log::info($t);
            return response([], 500);
        }
        DB::commit();
        session()->flash('success', 'Kursi penumpang berhasil diubah');
        return response([$froms, $tos], 200);
    }

    public function export(Request $request)
    {
        $area_id = $request->area_id;
        $date = $request->date;
        $fleet_route_id = $request->fleet_route_id;
        $data['langsir'] = OrderDetail::whereHas('order', function ($q) use ($date, $area_id, $fleet_route_id) {
            $q->whereIn('status', Order::STATUS_PAID_CUST)
                ->when($date, function ($query) use ($date) {
                    $query->whereDate('reserve_at', $date);
                })
                ->when($area_id, function ($query) use ($area_id) {
                    $query->whereHas('fleet_route.route.checkpoints', function ($subquery) use ($area_id) {
                        $subquery->whereHas('agency.city', function ($subsubquery) use ($area_id) {
                            $subsubquery->where('area_id', '!=', $area_id);
                        });
                    });
                })->when($fleet_route_id, function ($query) use ($fleet_route_id) {
                    $query->where('fleet_route_id', $fleet_route_id);
                });
        })
            ->with('order.fleet_route.fleet_detail.fleet.fleetclass', 'order.fleet_route.route')
            ->with('order.fleet_route.fleet_detail.fleet.layout', 'chair')
            ->get()
            ->sortBy('chair.name');
        $data['agencies'] = Agency::whereHas('city', function ($query) use ($area_id) {
            $query->whereAreaId($area_id);
        })->get();

        $data['date'] = $date;
        $data['fleet_route_id'] = $fleet_route_id;

        $pdf = \PDF::loadView('excel_export.langsir', $data);
        $pdf->stream('document.pdf');
    }
}
