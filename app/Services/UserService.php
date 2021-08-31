<?php

namespace App\Services;

use App\Http\Requests\Api\ApiRegisterCustomerRequest;
use App\Models\User;
use App\Models\Order;
use App\Repositories\UserRepository;
use App\Utils\Image;
use App\Utils\Response;
use Illuminate\Http\UploadedFile;

class UserService {
    use Response;

    public static function register(array $data, array $order_id = []) {
        Image::uploadFile($data['avatar'], 'avatar');
        $user = User::create($data);
        // compile order sebelum login jadi riwayat
        if($order_id && !empty($order_id)){
            Order::whereIn('id', $order_id)->update([
                'user_id'   => $user->id,
            ]);
        }
        $user->token = AuthService::login($user, 'CUSTOMER', null, $data['uuid']);
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
        try {
            $user = UserRepository::findByToken($token);
        } catch (\Throwable $e) {
            $user = null;
        }

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

    public static function updateFcmToken(User $user = null, $token = null) {
        if($user){
            $user->update([
                'fcm_token' => $token
            ]);
            $user->refresh();
        }
        return $user;
    }
}
        