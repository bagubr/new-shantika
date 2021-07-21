<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Route;
use App\Utils\Response;

class OrderService {
    use Response;

    public static function create(Order $data, $detail) {
        $route = Route::find($data->route_id)
            ?? (new self)->sendFailedResponse([], 'Rute perjalanan tidak ditemukan');

        if(!$data->price) {
            $data->price = $route->price;
        }
        if(!$data->code_order) {
            $data->code_order = self::generateCodeOrder();
        }
        if(!$data->status) {
            $data->status = Order::STATUS1;
        }
        if(!$data->expired_at) {
            $data->expired_at = self::getExpiredAt();
        }
        $order = Order::create($data->toArray());
            foreach($detail->layout_chair_id as $layout_chair_id) {
                OrderDetail::create([
                    'order_id'          => $order->id,
                    'layout_chair_id'   => $layout_chair_id,
                    'code_ticket'       => self::generateCodeOrder(),
                    'name'              => $detail->name,
                    'phone'             => $detail->phone,
                    'email'             => $detail->email,
                    'is_feed'           => $detail->is_feed,
                    'is_travel'         => $detail->is_travel,
                    'is_member'         => $detail->is_member
                ]);
            }

        return $order;
    } 

    public static function generateCodeOrder() {
        return 'STK-'.date('Ymdhis');
    }

    public static function getExpiredAt() {
        return date('Y-m-d H:i:s', strtotime("+1 day"));
    }
}