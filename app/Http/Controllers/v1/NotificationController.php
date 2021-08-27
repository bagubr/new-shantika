<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        return $this->sendSuccessResponse([
            'notifications'=>NotificationRepository::getAllByUserId($user->id)
        ]);
    }

    public function indexUnread(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        return $this->sendSuccessResponse([
            'notifications'=>NotificationRepository::getUnreadNotificationByUserId($user->id)
        ]);
    }

    public function read(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        $notification = Notification::findOrFail($request->id);
        $notification = NotificationService::read($notification);

        return $this->sendSuccessResponse([
            'notification'=>$notification
        ]);
    }

    public function readAll(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        $notification = NotificationService::readAll($user->id);

        return $this->sendSuccessResponse([]);
    }
}
