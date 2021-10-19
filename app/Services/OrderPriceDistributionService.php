<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPriceDistribution;
use App\Models\Setting;
use App\Repositories\UserRepository;

use function PHPSTORM_META\map;

class OrderPriceDistributionService {
    public static function createByOrderDetail(Order $order, array $order_details) {
        $total_price = self::calculateDistribution($order, $order_details);

        $price_distribution = OrderPriceDistribution::create(array_merge($total_price, [
            'order_id'=>$order->id,
        ]));
        unset($total_price['ticket_only']);

        return $price_distribution;
    }

    public static function calculateDistribution($order, $order_details) {
        $setting = Setting::first();
        $price_food = $order->fleet_route?->fleet_detail?->fleet?->fleetclass?->price_food;
        $total_price = [
            'for_food'=>0,
            'for_travel'=>0,
            'for_member'=>0,
            'for_agent'=>0,
            'total_deposit'=>0,
            'ticket_only'=>($order->price) - ($price_food * count($order_details)),
            'ticket_price'=>$order->agency_destiny?->city?->area_id == 1 
                ? $order->agency->prices->sortByDesc('created_at')->first()->price
                : $order->agency_destiny->prices->sortByDesc('created_at')->first()->price, 
            'food'=>0
        ];
        $total_price['ticket_price'] += $order->fleet_route->prices()->whereDate('start_at', '<=', $order->reserve_at)
        ->whereDate('end_at', '>=', $order->reserve_at)
        ->orderBy('id', 'desc')
        ->first()->true_deviation_price;
        $total_price['for_agent'] = $total_price['ticket_only'] * $setting->commision;
        
        foreach($order_details as $order_detail) {
            if($order_detail->is_feed) {
                $total_price['for_food'] +=  $price_food;
                $total_price['food'] += $price_food;
            } else {
                $total_price['food'] += $price_food - $setting->default_food_price;
            }
            if($order_detail->is_travel) {
                $total_price['for_travel'] += $setting->travel;
            }
            if($order_detail->is_member) {
                $total_price['for_member'] -= $setting->member;
            }
        }

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
            $total_price['total_deposit'] = $total_price['for_owner_gross'];
        }

        return $total_price;
    }
}