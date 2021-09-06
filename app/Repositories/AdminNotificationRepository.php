<?php

namespace App\Repositories;

use App\Models\AdminNotification;

class AdminNotificationRepository {
    public function all() {
        return AdminNotification::all();
    }
}
        