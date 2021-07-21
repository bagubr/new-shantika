<?php

namespace App\Interfaces;

class BookingInterface {
    public function __construct(
        public int $route_id,
        public int $layout_chair_id,
        public string $expired_at,
        public int $user_id
    ) {}
}