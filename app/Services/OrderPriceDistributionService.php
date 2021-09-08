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
        } else {
            $total_price['for_agent'] = 0;
        }
        $total_price['for_owner'] = $total_price['ticket_only'] - $total_price['for_travel'] - (-1 * $total_price['for_member']) - (-1 * $total_price['for_agent']);
        
        $price_distribution = OrderPriceDistribution::create(array_merge($total_price, [
            'order_id'=>$order->id,
        ]));
        unset($total_price['ticket_only']);

        return $price_distribution;
    }

    public static function calculateDistribution($order, $order_details) {
        $ticket_only = $order->fleet_route?->price - $order->fleet_route?->fleet_detail?->fleet?->fleetclass?->price_food;
        $total_price = [
            'for_food'=>$order?->fleet_route?->fleet_detail?->fleet?->fleetclass?->price_food * count($order_details),
            'for_travel'=>0,
            'for_member'=>0,
            'for_agent'=>$ticket_only * count($order_details),
            'ticket_only'=>$ticket_only * count($order_details)
        ];
        $setting = Setting::first();
        foreach($order_details as $order_detail) {
            if(!$order_detail->is_feed) {
                $total_price['for_food'] -=  $order->fleet_route?->fleet?->fleetclass?->price_food;
                $total_price['for_agent'] -=  $order->fleet_route?->fleet?->fleetclass?->price_food;
            }
            if($order_detail->is_travel) {
                $total_price['for_travel'] += $setting->travel;
            }
            if($order_detail->is_member) {
                $total_price['for_member'] -= $setting->member;
                $total_price['for_agent'] -= $setting->member;
            }
        }
        return $total_price;
    }
}