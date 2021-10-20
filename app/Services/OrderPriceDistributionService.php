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

        $total_price['ticket_only'] = $order_details[0]->is_feed
        ? $order->price * count($order_details)
        : ($order->price - $setting->default_food_price) * count($order_details);
        $total_price['ticket_price'] = $order->price;
        $total_price['for_food'] = (
            $order_details[0]->is_feed
            ? $price_food * count($order_details)
            : 0
        );
        $total_price['for_food'] = $order->agency->city->area_id == 2 ?: 0;
        $total_price['for_travel'] = (
            $order_details[0]->is_travel
                ? $setting->travel * count($order_details)
                : 0
        );
        $total_price['for_member'] = (
            $order_details[0]->is_travel
                ? $setting->travel * count($order_details)
                : 0
        );
        $total_price['for_agent'] = -1 * (
            (($for_deposit - $price_food) * count($order_details)) * $setting->commision
        );
        $total_price['total_deposit'] = (
            ($total_price['ticket_only'] * count($order_details)) + abs($total_price['for_travel']) - abs($total_price['for_member']) - abs($total_price['for_agent']) + (
                $order_details[0]->is_feed ? $total_price['for_food'] : ($setting->default_food_price * count($order_details))
            ) 
        );
        $total_price['for_owner'] = $total_price['total_deposit'];
        $total_price['for_owner_with_food'] = $total_price['total_deposit'];
        $total_price['for_owner_gross'] = $total_price['total_deposit'];           

        $is_agent = UserRepository::findUserIsAgent($order->user_id);
        if(!$is_agent && $order->status == Order::STATUS1) {
            $total_price['for_agent'] = 0;
            $total_price['for_owner'] = 0;
            $total_price['for_owner_with_food'] = 0;
            $total_price['for_owner_gross'] = 0;
            $total_price['total_deposit'] = 0;
        }   

        return $total_price;
    }
}