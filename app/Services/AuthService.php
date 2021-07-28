<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserToken;
use App\Repositories\UserRepository;
use App\Utils\Response;
use Illuminate\Support\Facades\Hash;

class AuthService {
    use Response;

    public static function login($user, $fcm_token = '', $phone = '',$uuid = '') {
        if($user == null) (new self)->sendFailedResponse([], "Sepertinya akun anda belum terdaftar");
        $token = self::generateToken($user);
        $user = self::authenticate($user, $token, $fcm_token, $uuid);
        return $user;
    }
    
    public static function loginByEmail($user, $fcm_token = '', $email = '',$uuid = '') {
        if($user == null) (new self)->sendFailedResponse([], "Sepertinya akun anda belum terdaftar");
        $user = self::authenticate($user, $fcm_token, $uuid);
        return $user;
    }

    public static function authenticate(User $user, $fcm_token = '', $uuid = '') {
        if(UserRepository::findUserIsAgent($user->id)) {
            $token = self::generateToken($user, false);
            UserToken::updateOrCreate([
                'token'=>$token
            ],[
                'user_id'=>$user->id,
                'user_agent'=>request()->userAgent()
            ]);
        } else {
            $token = self::generateToken($user, true);
            $user->update([
                'uuid'=>$uuid,
                'token'=>$token,
                'fcm_token'=>$fcm_token
            ]);
        }
        return $token;
    }
    
    private static function generateToken($user, $withUserAgent = true) {
        $token = '';
        if($withUserAgent) {
            $token = md5(request()->userAgent().env('API_KEY', ''));
            $token .= '.';
        } 
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $salt = "";
        for ($i = 0; $i < 36; $i++) {
            $token .= $char = $characters[rand(0, $charactersLength - 1)];
            $salt .= $char;
        }

        return $token;
    }
}
        