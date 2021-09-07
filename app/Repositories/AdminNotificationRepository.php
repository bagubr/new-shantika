<?php

namespace App\Repositories;

use App\Models\AdminNotification;

class AdminNotificationRepository {
    public static function all() {
        return AdminNotification::limit(10)->get();
    }
}
        