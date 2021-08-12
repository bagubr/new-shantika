<?php

namespace App\Services;

use App\Events\SendingNotification;
use App\Jobs\Notification\TicketExchangedJob;
use App\Models\Agency;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPriceDistribution;
use App\Models\Payment;
use App\Models\Route;
use App\Models\Setting;
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
        $route = Route::find($data->route_id)
            ?? (new self)->sendFailedResponse([], 'Rute perjalanan tidak ditemukan');
        $order_exists = OrderRepository::isOrderUnavailable($data->route_id, $data->reserve_at, $detail->layout_chair_id);
        if($order_exists) {
            (new self)->sendFailedResponse([], 'Maaf, kursi sudah dibeli oleh orang lain, silahkan pilih kursi lain');
        }
        $setting = Setting::first();
        $ticket_price = $route->price - $data->route->fleet->fleetclass->price_food;
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
        Log::info($order);
        $notification = Notification::build(
            NotificationMessage::successfullySendingTicket()[0],
            NotificationMessage::successfullySendingTicket()[1],
            Notification::TYPE1,
            $order->id,
            $order->user_id
        );
        SendingNotification::dispatch($notification, $order->user?->fcm_token, true);
    }

    public static function createDetail($order, $layout_chairs, $detail) {
        $order_details = [];
        foreach($layout_chairs as $layout_chair_id) {
            $order_details[] = OrderDetail::create([
                'order_id'          => $order->id,
                'layout_chair_id'   => $layout_chair_id,
                'name'              => $detail->name,
                'phone'             => $detail->phone,
                'email'             => $detail->email ?? $order->user->email,
                'is_feed'           => $detail->is_feed,
                'is_travel'         => $detail->is_travel,
                'is_member'         => $detail->is_member
            ]);
        }
        $distrib = OrderPriceDistributionService::createByOrderDetail($order, $order_details);
    }

    public static function getInvoice(Payment|int|null $payment = null) {
        if($payment == null) {
            return '';
        }
        if($payment instanceof int) {
            $payment = Payment::find($payment);
        }
        
        return PaymentService::getSecretAttribute($payment);
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

        TicketExchangedJob::dispatchAfterResponse($order);

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