<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPriceDistribution;
use App\Models\Payment;
use App\Models\Route;
use App\Utils\Response;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService {
    use Response;

    public static function create(Order $data, $detail, $payment_type_id = null) {
        $route = Route::find($data->route_id)
            ?? (new self)->sendFailedResponse([], 'Rute perjalanan tidak ditemukan');

        if(!$data->price) {
            $data->price = ($route->price * count($detail->layout_chair_id));
            if($detail->is_feed){
                $data->price += ($data->route->fleet->fleetclass->price_food * count($detail->layout_chair_id));
            }
            if($detail->is_travel){
                $data->price += (config('application.price_list.travel') * count($detail->layout_chair_id));
            }
            if($detail->is_member){
                $data->price -= (config('application.price_list.member') * count($detail->layout_chair_id));
            }
        }
        if(!$data->code_order) {
            $data->code_order = '';
        }
        if(empty($data->status)) {
            $data->status = Order::STATUS1;
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

        return $order;
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
        OrderPriceDistributionService::createByOrderDetail($order, $order_details);
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
        if(@$order->route?->checkpoints[0]->agency_id != $agency_id) {
            $agency = Agency::find($agency_id);
            (new self)->sendFailedResponse([], 'Maaf, anda hanya dapat menukarkan tiket di agen '.$agency->name);
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