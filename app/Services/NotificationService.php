<?php

namespace App\Services;

use App\Models\Notification;
use App\Repositories\UserRepository;

class NotificationService {
    public static function read(Notification $notification) {
        $notification->update(['is_seen'=>true]);
        $notification->refresh();
        return $notification;
    }

    public static function readAll($user_id) {
        $notification = Notification::where('user_id', $user_id)->update([
            'is_seen'=>true
        ]);

        return $notification;
    }
}
        