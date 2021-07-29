<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderPriceDistribution;

class OrderPriceDistributionRepository {
    public static function getSumDepositOfAgencyByDate($token, $date) {
        $user = UserRepository::findByToken($token);
        return OrderPriceDistribution::whereHas('order', function($query) use ($user, $date) {
            $query->whereHas('user.agencies', function($subquery) use ($user) {
                $subquery->where('id', $user->agencies->id);
            })
            ->where('status', Order::STATUS3)
            ->whereDate('created_at',$date);
        })->sum('for_owner');
    }

    public static function getSumCommisionOfAgencyByDate($token, $date) {
        $user = UserRepository::findByToken($token);
        $sum = OrderPriceDistribution::whereHas('order', function($query) use ($user, $date) {
            $query->whereHas('user.agencies', function($subquery) use ($user) {
                $subquery->where('id', $user->agencies->id);
            })
            ->where('status', Order::STATUS3)
            ->whereDate('created_at',$date);
        })->sum('for_agent');
        return abs($sum);
    }
}
        