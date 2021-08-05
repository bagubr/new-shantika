<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPriceDistribution;
use App\Models\Setting;
use App\Repositories\UserRepository;

class OrderPriceDistributionService {
    public static function createByOrderDetail(Order $order, array $order_details) {
        $total_price = self::calculateDistribution($order, $order_details);
        $setting = Setting::first();
        
        $is_agent = UserRepository::findUserIsAgent($order->user_id);
        if($is_agent) {
            $total_price['for_agent'] = -1 * round($setting->commision * $total_price['for_agent']);
            $total_price['for_owner'] = $order->price + array_sum($total_price);
        } else {
            $total_price['for_agent'] = 0;
            $total_price['for_owner'] = $order->price + array_sum($total_price);
        }

        
        $price_distribution = OrderPriceDistribution::create(array_merge($total_price, [
            'order_id'=>$order->id,
        ]));
        unset($total_price['ticket_only']);

        return $price_distribution;
    }

    public static function calculateDistribution($order, $order_details) {
        $total_price = [
            'for_food'=>0,
            'for_travel'=>0,
            'for_member'=>0,
            'for_agent'=>0, 
            'ticket_only'=>$order->route?->price
        ];
        $setting = Setting::first();
        foreach($order_details as $order_detail) {
            $total_price['for_agent'] = $order->price;
            if($order_detail->is_feed) {
                $total_price['for_food'] += $order->route?->fleet?->fleetclass?->price_food;
                $total_price['for_agent'] -= $order->route?->fleet?->fleetclass?->price_food; 
            }
            if($order_detail->is_travel) {
                $total_price['for_travel'] += $setting->travel;
                $total_price['for_agent'] -= $setting->travel;
            }
            if($order_detail->is_member) {
                $total_price['for_member'] -= $setting->member;
                $total_price['for_agent'] -= $setting->member;
            }
        }

        return $total_price;
    }
}