<?php

namespace App\Services;

use App\Events\SendingNotification;
use App\Models\Notification;
use App\Models\Order;
use App\Models\TimeChangeRoute;
use App\Utils\NotificationMessage;

class TimeChangeService {
    public static function create($data)
    {
        $time_change = TimeChangeRoute::create($data);

        $order = Order::with('user')->whereHas('user', function ($query)
        {
            $query->whereNotNull('fcm_token');
        })->whereDate('reserve_at', $data['date'])->where('fleet_route_id' ,$data['fleet_route_id']);
        $order->update(['time_classification_id' => $data['time_classification_id']]);
        $order = Order::with('user')->whereHas('user', function ($query)
        {
            $query->whereNotNull('fcm_token');
        })->whereDate('reserve_at', $data['date'])->where('fleet_route_id' ,$data['fleet_route_id'])->get();
        // $order->refresh();
        self::sendNotification($order->pluck('user.fcm_token', 'user_id'), $time_change);
        return $time_change;
    }

    public static function update($time_change, $data)
    {
        $time_change->update($data);

        $order = Order::with('user')->whereHas('user', function ($query)
        {
            $query->whereNotNull('fcm_token');
        })->whereDate('reserve_at', $time_change->date)->where('fleet_route_id' ,$time_change->fleet_route_id);
        $order->update(['time_classification_id' => $time_change->time_classification_id]);
        $order = Order::with('user')->whereHas('user', function ($query)
        {
            $query->whereNotNull('fcm_token');
        })->whereDate('reserve_at', $time_change->date)->where('fleet_route_id' ,$time_change->fleet_route_id)->get();
        // $order->refresh();
        self::sendNotification($order->pluck('user.fcm_token', 'user_id'), $time_change);
        return $time_change;
    }

    public static function sendNotification($user, $time_change)
    {
        foreach ($user as $key => $value) {
            $message = NotificationMessage::timeChange($time_change);
            $notification = Notification::build($message[0], $message[1], Notification::TYPE6, $time_change->id, $key);   
            SendingNotification::dispatch($notification, $value, true);
        }
    }
}
        