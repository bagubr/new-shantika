<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPriceDistribution;
use App\Repositories\UserRepository;

class OrderPriceDistributionService {
    public static function createByOrderDetail(Order $order, array $order_details) {
        $total_price = self::calculateDistribution($order, $order_details);
        
        $is_agent = UserRepository::findUserIsAgent($order->user_id);
        if($is_agent) {
            $total_price['for_agent'] = -1 * round(config('application.commision', 0.08) * $total_price['for_agent']);
            $total_price['for_owner'] = $order->price + array_sum($total_price);
        } else {
            $total_price['for_agent'] = 0;
            $total_price['for_owner'] = $order->price + array_sum($total_price);
        }

        
        $price_distribution = OrderPriceDistribution::create(array_merge($total_price, [
            'order_id'=>$order->id
        ]));

        return $price_distribution;
    }

    public static function calculateDistribution($order, $order_details) {
        $total_price = [
            'for_food'=>0,
            'for_travel'=>0,
            'for_member'=>0,
            'for_agent'=>0,        
        ];
        foreach($order_details as $order_detail) {
            $total_price['for_agent'] = $order->price;
            if($order_detail->is_feed) {
                $total_price['for_food'] += config('application.price_list.food');
                $total_price['for_agent'] -= config('application.price_list.food');
            }
            if($order_detail->is_travel) {
                $total_price['for_travel'] += config('application.price_list.travel');
                $total_price['for_agent'] -= config('application.price_list.food');
            }
            if($order_detail->is_member) {
                $total_price['for_member'] -= config('application.price_list.member');
                $total_price['for_agent'] -= config('application.price_list.food');
            }
        }

        return $total_price;
    }
}