<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;

class LayoutChairRepository {
    public static function countBoughtByAgencyByDate($token, $date) {
        $user = UserRepository::findByToken($token);

        return OrderDetail::whereHas('order', function($query) use ($user, $date) {
            $query->whereHas('user.agencies', function($subquery) use ($user, $date) {
                $subquery->where('id', $user->agencies->id);
            })
            ->where('status', Order::STATUS3)
            ->whereDate('created_at', $date);
        })
        ->count();
    }
}
        