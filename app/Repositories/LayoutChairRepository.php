<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;

class LayoutChairRepository {
    public static function countBoughtByAgencyByDate($token, $date) {
        $user_id = UserRepository::findByToken($token)?->id;

        return OrderDetail::whereHas('order', function($query) use ($user_id, $date) {
            $query->where(function($subquery) use ($user_id) {
                $subquery->where(function($subsubquery) use ($user_id) {
                    $subsubquery->where('departure_agency_id', $user_id)
                        ->whereHas('user.agencies')
                        ->whereIn('status', [Order::STATUS3]);
                })
                ->orWhere(function($subsubquery) use ($user_id) {
                    $subsubquery->where('departure_agency_id', $user_id)
                    ->whereDoesntHave('user.agencies')
                    ->whereIn('status', [Order::STATUS5, Order::STATUS8]);
                });
            })
            ->where('status', Order::STATUS3)
            ->whereDate('created_at', $date);
        })
        ->count();
    }
}
        