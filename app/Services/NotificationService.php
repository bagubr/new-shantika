<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService {
    public static function read(Notification $notification) {
        $notification->update(['is_seen'=>true]);
        $notification->refresh();
        return $notification;
    }
}
        