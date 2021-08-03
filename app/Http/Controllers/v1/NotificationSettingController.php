<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiNotificationSettingUpdateRequest;
use App\Models\NotificationSetting;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
    public function show(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        return $this->sendSuccessResponse([
            'setting'=>NotificationSetting::where('user_id', $user->id)->first() ?? (object) []
        ]);
    }

    public function update(ApiNotificationSettingUpdateRequest $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        $setting = NotificationSetting::where('user_id', $user->id)->updateOrCreate([
            'user_id'=>$user->id
        ], $request->only(['activity', 'remember', 'feature']));
        $setting->refresh();
        return $this->sendSuccessResponse([
            'setting'=>$setting
        ]);
    }
}
