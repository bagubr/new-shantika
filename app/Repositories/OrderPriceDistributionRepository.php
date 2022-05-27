<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderPriceDistribution;

class OrderPriceDistributionRepository
{
    public static function getSumDepositOfAgencyByDate($token, $date)
    {
        $user = UserRepository::findByToken($token);
        return OrderPriceDistribution::whereHas('order', function ($query) use ($user, $date) {
            $query->where(function($subquery) use ($user, $date) {
                $subquery->where(function($subsubquery) use ($user) {
                    $subsubquery->where('departure_agency_id', $user->agencies?->agent?->id)
                        ->whereHas('user.agencies')
                        ->whereIn('status', [Order::STATUS3]);
                })
                ->orWhere(function($subsubquery) use ($user) {
                    $subsubquery->where('departure_agency_id', $user->agencies?->agent?->id)
                    ->whereDoesntHave('user.agencies')
                    ->whereIn('status', [Order::STATUS5, Order::STATUS8]);
                });
            })
            ->whereDate('reserve_at', $date);
        })->sum('total_deposit');
    }

    public static function getSumCommisionOfAgencyByDate($token, $date)
    {
        $user = UserRepository::findByToken($token);
        $sum = OrderPriceDistribution::whereHas('order', function ($query) use ($user, $date) {
            $query->where(function($subquery) use ($user) {
                $subquery->where(function($subsubquery) use ($user) {
                    $subsubquery->where('departure_agency_id', $user->agencies?->agent?->id)
                    ->whereHas('user.agencies')
                    ->whereIn('status', [Order::STATUS3]);
                })
                ->orWhere(function($subsubquery) use ($user) {
                    $subsubquery->where('departure_agency_id', $user->agencies?->agent?->id)
                    ->whereDoesntHave('user.agencies')
                    ->whereIn('status', [Order::STATUS5, Order::STATUS8]);
                });
            })
            ->whereDate('reserve_at', $date);
        })->sum('for_agent');
        return abs($sum);
    }

    public static function findById($order)
    {
        return OrderPriceDistribution::where('order_id', $order)->first();
    }
}
