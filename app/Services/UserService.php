<?php

namespace App\Services;

use App\Http\Requests\Api\ApiRegisterCustomerRequest;
use App\Models\User;
use App\Utils\Image;

class UserService {
    public function register(array $data) {
        Image::uploadFile($data['avatar'], 'avatar');
        $user = User::create($data);
        $user = AuthService::login($user, null, null, $data['uid']);
        return $user;
    }
}
        