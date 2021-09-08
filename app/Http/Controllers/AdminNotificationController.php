<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function show($id) {
        $notification = AdminNotification::find($id);
        if(empty($notification)) {
            return back()->with('error', 'Data notifikasi tidak ditemukan');
        }

        switch ($notification->type) {
            case Notification::TYPE1:
                if(Order::where('id', $notification->reference_id)->exists()) {
                    return redirect()->route('order.detail', $notification->reference_id);
                } else {
                    return back()->with('error', 'Data notifikasi tidak ditemukan');
                }
                break;
            default:
                return back()->with('error', 'Data notifikasi tidak ditemukan');
        }
    }
}
