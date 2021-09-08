<?php

namespace App\Services;

use App\Events\SendingNotification;
use App\Jobs\Admin\NewOrderNotification;
use App\Jobs\Notification\TicketExchangedJob;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Agency;
use App\Models\FleetRoute;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPriceDistribution;
use App\Models\Payment;
use App\Models\Route;
use App\Models\Setting;
use App\Repositories\BookingRepository;
use App\Repositories\OrderRepository;
use App\Utils\Response;
use App\Repositories\UserRepository;
use App\Utils\NotificationMessage;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService {
    use Response;

    public static function create(Order $data, $detail, $payment_type_id = null) {
        $route = FleetRoute::find($data->fleet_route_id)
            ?? (new self)->sendFailedResponse([], 'Rute perjalanan tidak ditemukan');
        $order_exists = OrderRepository::isOrderUnavailable($data->fleet_route_id, $data->reserve_at, $detail->layout_chair_id);
        $booking_exists = BookingRepository::isBooked($data->fleet_route_id, $data->user_id, $detail->layout_chair_id, $data->reserve_at);
        if($order_exists) {
            (new self)->sendFailedResponse([], 'Maaf, kursi sudah dibeli oleh orang lain, silahkan pilih kursi lain');
        }
        if($booking_exists) {
            (new self)->sendFailedResponse([], "Maaf, kursi anda telah dibooking terlebih dahulu oleh orang lain");
        }
        $setting = Setting::first();
        $ticket_price = $route->price - $data->fleet_route->fleet_detail->fleet->fleetclass->price_food;
        $ticket_price_with_food = $detail->is_feed
            ? $route->price * count($detail->layout_chair_id)
            : $ticket_price * count($detail->layout_chair_id);
        $data->price = $ticket_price_with_food;
        if($detail->is_travel){
            $price_travel = $setting->travel * count($detail->layout_chair_id);
            $data->price += $price_travel;
        }
        if($detail->is_member){
            $price_member = $setting->member * count($detail->layout_chair_id);
            $data->price -= $price_member;
        }
        if(!$data->code_order) {
            $data->code_order = '';
        }
        if(!$data->expired_at) {
            $data->expired_at = self::getExpiredAt();
        }
        $order = Order::create($data->toArray());
        if(!$data->code_order && !$order->code_order) {
            $order->code_order = self::generateCodeOrder($order->id);
            $order->save();
        }
        if($detail->code_booking) {
            BookingService::deleteByCodeBooking($detail->code_booking);
        }
        self::createDetail($order, $detail->layout_chair_id, $detail);
        $order = Order::find($order->id);
        self::sendNotification($order);
        return $order;
    } 

    private static function sendNotification($order) {
        $message = NotificationMessage::successfullySendingTicket();
        $notification = Notification::build(
            $message[0],
            $message[1],
            Notification::TYPE1,
            $order->id,
            $order->user_id
        );
        $message = NotificationMessage::newTicketOrder($order);
        $admin_notification = AdminNotification::build(
            $message[0],
            $message[1],
            Notification::TYPE1,
            $order->id
        );
        if(empty($order->user?->agencies)) {
            SendingNotification::dispatch($notification, $order->user?->fcm_token, false);
        }
        NewOrderNotification::dispatch($admin_notification, Admin::whereNotNull('fcm_token')->pluck('fcm_token'), true);
    }

    public static function createDetail($order, $layout_chairs, $detail) {
        $order_details = [];
        foreach($layout_chairs as $layout_chair_id) {
            $order_details[] = OrderDetail::create([
                'order_id'          => $order->id,
                'layout_chair_id'   => $layout_chair_id,
                'name'              => $detail->name,
                'phone'             => $detail->phone,
                'email'             => $detail->email ?? $order->user?->email ?? $order->agency->users()->whereNotNull('email')->pluck('email')[0],
                'is_feed'           => $detail->is_feed,
                'is_travel'         => $detail->is_travel,
                'is_member'         => $detail->is_member
            ]);
        }
        $distrib = OrderPriceDistributionService::createByOrderDetail($order, $order_details);
    }

    public static function getInvoice(Payment|int|null $payment = null) {
        if(empty($payment)) {
            return '';
        }
        if($payment instanceof int) {
            $payment = Payment::find($payment);
        }
        
        return @PaymentService::getSecretAttribute($payment) ?? "";
    }

    public static function exchangeTicket(Order &$order, $agency_id) {
        if(@$order->departure_agency_id != $agency_id) {
            (new self)->sendFailedResponse([], 'Maaf, anda hanya dapat menukarkan tiket di agen keberangkatan tiket');
        }
        if($order->status != Order::STATUS3) {
            (new self)->sendFailedResponse([], 'Maaf, penumpang / customer harus membayar terlebih dahulu');
        }
        DB::beginTransaction();
        $order->update([
            'status'=>Order::STATUS5,
            'exchanged_at'=>date('Y-m-d H:i:s')
        ]);
        $order->distribution()->update([
            'for_agent'=>OrderPriceDistributionService::calculateDistribution($order, $order->order_detail)['for_agent']
        ]);
        DB::commit();
        $order->refresh();

        TicketExchangedJob::dispatch($order);

        return $order;
    }

    public static function updateStatus(Order|int $order, $status) {
        if(is_int($order)) {
            $order = Order::find($order);
        }

        $order->update([
            'status'=>$status,
        ]);

        $order->payment()->update([
            'status'=>$status
        ]);
        $order->refresh();

        return $order;
    }

    public static function generateCodeOrder($id) {
        return 'NS'.str_pad($id, 8, '0', STR_PAD_LEFT);
    }

    public static function getExpiredAt() {
        return date('Y-m-d H:i:s', strtotime("+1 day"));
    }
}