<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPriceDistribution;
use App\Models\Setting;
use App\Repositories\UserRepository;

class OrderPriceDistributionService {
    public static function createByOrderDetail(Order $order, array $order_details, $price) {
        $total_price = self::calculateDistribution($order, $order_details, $price);

        $price_distribution = OrderPriceDistribution::create(array_merge($total_price, [
            'order_id'=>$order->id,
        ]));
        unset($total_price['ticket_only']);

        return $price_distribution;
    }

    public static function calculateDistribution($order, $order_details, $for_deposit) {
        $setting = Setting::first();
        $price_food = $order->fleet_route?->fleet_detail?->fleet?->fleetclass?->price_food;
        $total_price = [
            'for_food'=>0,
            'for_travel'=>$order_details[0]->is_travel
                ? $setting->travel * count($order_details)
                : 0,
            'for_member'=>$order_details[0]->is_member
                ? $setting->member * count($order_details)
                : 0,
            'for_agent'=>0,
            'total_deposit'=>0,
            'ticket_only'=>$order_details[0]->is_feed 
                ? $order->price - ($price_food * count($order_details)) 
                : $order->price,
            'ticket_price'=>$order->price, 
            'food'=>0
        ];
        foreach($order_details as $order_detail) {
            if($order_detail->is_feed) {
                $total_price['for_food'] +=  $price_food;
                $total_price['food'] += $price_food;
            } else {
                $total_price['food'] += $price_food - $setting->default_food_price;
            }
        }
        $total_price['ticket_only'] = $total_price['ticket_only'] - $total_price['for_travel'] - abs($total_price['for_member']);

        $total_price['for_agent'] = ($for_deposit - $price_food) * $setting->commision;
        $is_agent = UserRepository::findUserIsAgent($order->user_id);
        if(!$is_agent && $order->status == Order::STATUS1) {
            $total_price['for_agent'] = 0;
            $total_price['for_owner'] = 0;
            $total_price['for_owner_with_food'] = 0;
            $total_price['for_owner_gross'] = 0;
            $total_price['total_deposit'] = 0;
        } else {
            $total_price['for_agent'] *= -1;
            $total_price['for_owner'] = $total_price['ticket_only'] - $total_price['for_travel'] - (-1 * $total_price['for_member']) - (-1 * $total_price['for_agent']);
            $total_price['for_owner_with_food'] = $total_price['ticket_only'] + $total_price['food'] - $total_price['for_travel'] - (-1 * $total_price['for_member']) - (-1 * $total_price['for_agent']);
            $total_price['for_owner_gross'] = $total_price['ticket_only'] + $total_price['food'] + $total_price['for_travel'] - (-1 * $total_price['for_member']) - (-1 * $total_price['for_agent']);           
            $total_price['total_deposit'] = $for_deposit - $price_food + $total_price['food'];
        }

        if($order->agency->city->area_id == 2) {
            $total_price['for_food'] = 0;
        }

        return $total_price;
    }
}