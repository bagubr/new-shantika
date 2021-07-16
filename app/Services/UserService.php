<?php

namespace App\Services;

use App\Http\Requests\Api\ApiRegisterCustomerRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Utils\Image;
use App\Utils\Response;
use Illuminate\Http\UploadedFile;

class UserService {
    use Response;

    public static function register(array $data) {
        Image::uploadFile($data['avatar'], 'avatar');
        $user = User::create($data);
        $user = AuthService::login($user, null, null, $data['uuid']);
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

    public static function getAuthenticatedUser($token) {
        $user = UserRepository::findByToken($token);

        return $user 
            ? $user 
            : (new self)->sendFailedResponse([], 'Oops, sepertinya anda harus login ulang');
    }

    public static function updateProfile(User $user, array $data) {
        Image::uploadFile($data['avatar'], 'avatar');
        $user->update($data);
        $user->refresh();

        return $user;
    }
}
        