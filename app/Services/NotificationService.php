<?php

namespace App\Services;

use App\Models\Notification;
use App\Repositories\UserRepository;

class NotificationService {
    public static function sendNotification() {

    }

    public static function sendNotificationToTopic() {
        
    }

    public static function read(Notification $notification) {
        $notification->update(['is_seen'=>true]);
        $notification->refresh();
        return $notification;
    }

    public static function readAll($token) {
        $user = UserRepository::findByToken($token);

        $notification = Notification::where('user_id', $user->id)->update([
            'is_seen'=>true
        ]);

        return $notification;
    }
}
        