<?php

namespace App\Http\Controllers;

use App\Events\SendingNotification;
use App\Exports\SketchLogsExport;
use App\Models\Admin;
use App\Models\Agency;
use App\Models\Fleet;
use App\Models\FleetRoute;
use App\Models\Notification;
use App\Models\Order;
use App\Models\SketchLog;
use App\Utils\NotificationMessage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SketchLogController extends Controller
{
    public function index(Request $request)
    {
        $test = $request->flash();

        $logs = SketchLog::orderBy('created_at', 'desc')
            ->when($request->admin_id, function ($q) use ($request) {
                $q->where('admin_id', $request->admin_id);
            })->when($request->agency_id, function ($q) use ($request) {
                $q->whereHas('order', function ($sq) use ($request) {
                    $sq->where('departure_agency_id', $request->agency_id);
                });
            })->when($request->from_fleet_id, function ($q) use ($request) {
                $q->whereHas('from_fleet_route.fleet_detail', function ($sq) use ($request) {
                    $sq->where('fleet_id', $request->from_fleet_id);
                });
            })->when($request->to_fleet_id, function ($q) use ($request) {
                $q->whereHas('from_fleet_route.fleet_detail', function ($sq) use ($request) {
                    $sq->where('fleet_id', $request->to_fleet_id);
                });
            })->when($request->from_date, function ($q) use ($request) {
                $q->where('from_date', '>=', $request->from_date);
            })->when($request->to_date, function ($q) use ($request) {
                $q->where('to_date', '<=', $request->to_date);
            })->paginate();
        return view('sketch.log', compact('test'), [
            'admins' => Admin::select('id', 'name')->get(),
            'fleets' => Fleet::select('id', 'name')->orderBy('name')->get(),
            'agencies' => Agency::select('id', 'name')->get(),
            'logs' => $logs
        ]);
    }
    public function export()
    {
        return Excel::download(new SketchLogsExport(), 'riwayat_sketch_' . date('dmYHis') . '.xlsx');
    }

    public function create(Request $request)
    {
        $data = $request->all();
        SketchLog::create($data);
    }

    public function notification(Request $request)
    {   
        return $request->cancelation_reason;
        $order = Order::find($request->id);
        $message = NotificationMessage::orderCanceled($order->fleet_route->fleet_detail->fleet->name, $request->cancelation_reason);
        $notification = Notification::build($message[0], $message[1], Notification::TYPE1, $order->id, $order->user_id);
        SendingNotification::dispatch($notification, $order?->user?->fcm_token, true);
    }
}
