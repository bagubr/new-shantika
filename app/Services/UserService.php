<?php

namespace App\Services;

use App\Http\Requests\Api\ApiRegisterCustomerRequest;
use App\Models\Membership;
use App\Models\MembershipPoint;
use App\Models\User;
use App\Models\Order;
<<<<<<< HEAD
=======
use App\Repositories\MembershipRepository;
>>>>>>> rilisv1
use App\Repositories\UserRepository;
use App\Utils\Image;
use App\Utils\Response;
use Illuminate\Http\UploadedFile;

class UserService {
    use Response;

    public static function register(array $data, array $order_id = []) {
        Image::uploadFile($data['avatar'], 'avatar');
        $user = User::create($data);
<<<<<<< HEAD
        $membership = Membership::create(['user_id' => $user->id]);
        MembershipPoint::create(['membership_id' => $membership->id, 'value' => 0, 'status' => 'create']);
=======
        MembershipService::create($user->id);
>>>>>>> rilisv1
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
            if(!empty($token)){
                $user->update([
                    'fcm_token' => $token
                ]);
                $user->refresh();
            }
        }
        return $user;
    }
}
