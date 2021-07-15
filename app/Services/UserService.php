<?php

namespace App\Services;

use App\Http\Requests\Api\ApiRegisterCustomerRequest;
use App\Models\User;
use App\Utils\Image;
use Illuminate\Http\UploadedFile;

class UserService {
    public static function register(array $data) {
        Image::uploadFile($data['avatar'], 'avatar');
        $user = User::create($data);
        $user = AuthService::login($user, null, null, $data['uid']);
        return $user;
    }

    public static function updateCustomerProfile(User $user, array $data) {
        if(@$data['avatar'] instanceof UploadedFile) {
            Image::uploadFile($data['avatar'], 'avatar');
        }
        $user->update($data);
        $user->refresh();
        return $user;
    }
}
        