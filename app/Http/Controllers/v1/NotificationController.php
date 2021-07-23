<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Repositories\NotificationRepository;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request) {
        return $this->sendSuccessResponse([
            'notifications'=>NotificationRepository::getAllByUserToken($request->bearerToken())
        ]);
    }

    public function indexUnread(Request $request) {
        return $this->sendSuccessResponse([
            'notifications'=>NotificationRepository::getUnreadNotificationByUserToken($request->bearerToken())
        ]);
    }

    public function read(Request $request) {
        $notification = Notification::findOrFail($request->id);
        $notification = NotificationService::read($notification);

        return $this->sendSuccessResponse([
            'notification'=>$notification
        ]);
    }

    public function readAll(Request $request) {
        $notification = NotificationService::readAll($request->bearerToken());

        return $this->sendSuccessResponse([]);
    }
}
