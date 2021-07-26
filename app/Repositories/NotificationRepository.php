<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository {
    public static function getUnreadNotificationByUserToken($token) {
        return Notification::whereHas('user', function($query) use ($token) {
            $query->where('token', $token);
        })->where('is_seen', false)->get();
    }

    public static function getAllByUserToken($token) {
        return Notification::whereHas('user', function($query) use ($token) {
            $query->where('token', $token);
        })->get();
    }
}
        