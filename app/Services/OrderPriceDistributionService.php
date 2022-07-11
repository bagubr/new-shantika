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


    public static function calculateDistribution($order, $order_details, $for_deposit, $price_food = null, $total_travel = null, $total_member = null) {
        $setting = Setting::first();
        if($total_travel == null){
            $total_travel = $setting->travel;
        }
        if($total_member == null){
            $total_member = $setting->member;
        }
        if($price_food == null){
            $price_food = $order->fleet_route?->fleet_detail?->fleet?->fleetclass?->price_food;
        }


        $total_price['ticket_price'] = $order->price;
        if(empty($order->user->agencies)) $total_price['ticket_price'] -= $setting->xendit_charge; 
        
        $total_price['for_food'] = $price_food * $order->order_detail->where('is_feed', 1)->count();
        $total_price['ticket_only'] = $for_deposit;
        // $total_price['for_food'] = $order->agency->city->area_id == 2 
        //  ? 0
        //  : $total_price['for_food'];
        $total_price['for_travel'] = $total_travel * $order->order_detail->where('is_travel', 1)->count();
        $total_price['for_member'] = $total_member * $order->order_detail->where('is_member', 1)->count();
        $total_price['for_agent'] =   $total_price['ticket_only'] * $setting->commision ;
        
        $total_price['for_owner'] = $total_price['ticket_only'] - $total_price['for_agent'];
        $total_price['total_deposit'] = $total_price['for_owner'] + $total_price['for_food'];
        $total_price['for_owner_with_food'] = $total_price['for_owner'] + $total_price['for_food'];
        $total_price['for_owner_gross'] = $total_price['ticket_price'] + $total_price['for_agent'];

        $is_agent = UserRepository::findUserIsAgent($order->user_id);
        $total_price['for_agent'] =   -1 * ($total_price['for_agent']);

        if(!$is_agent && $order->status == Order::STATUS1) {
            $total_price['charge'] = $setting->xendit_charge;
            $total_price['for_agent'] = 0;
            $total_price['for_owner'] = 0;
            $total_price['for_owner_with_food'] = 0;
            $total_price['for_owner_gross'] = 0;
            $total_price['total_deposit'] = 0;
        }   

        return $total_price;
    }
}