<?php

namespace App\Services;

class ReviewService {
    public static function sumByUserIdForAgent($user_id) : float {
        return (float) rand(0, 100) / 10;
    }
}
        