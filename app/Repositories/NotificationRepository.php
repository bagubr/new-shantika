<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository {
    public static function getUnreadNotificationByUserId($id) {
        return Notification::where('user_id', $id)->where('is_seen', false)->get();
    }

    public static function getAllByUserId($id) {
        return Notification::where('user_id', $id)->get();
    }
}
        