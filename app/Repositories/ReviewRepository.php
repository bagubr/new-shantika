<?php

namespace App\Repositories;

use App\Models\Review;

class ReviewRepository {
    public static function getHistoryOfAgent($user_id) {
        return Review::whereHas('order.route.checkpoints', function($query) use ($user_id) {
            $query->where('order', 0)->where('agency_id', $user_id);
        })->get();
    }
}
        