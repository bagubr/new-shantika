<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;

class LayoutChairRepository {
    public static function countBoughtByAgencyByDate($token, $date) {
        $agency_id = UserRepository::findByToken($token)?->agencies->agent->id;

        return OrderDetail::whereHas('order', function($query) use ($agency_id, $date) {
            $query->where(function($subquery) use ($agency_id) {
                $subquery->where(function($subsubquery) use ($agency_id) {
                    $subsubquery->where('departure_agency_id', $agency_id)
                        ->whereHas('user.agencies')
                        ->whereIn('status', [Order::STATUS3]);
                })
                ->orWhere(function($subsubquery) use ($agency_id) {
                    $subsubquery->where('departure_agency_id', $agency_id)
                    ->whereDoesntHave('user.agencies')
                    ->whereIn('status', [Order::STATUS5, Order::STATUS8]);
                });
            })
            ->whereDate('reserve_at', $date);
        })
        ->count();
    }
}
        