<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Utils\Response;
use Illuminate\Support\Facades\Hash;

class AuthService {
    use Response;

    public static function login($user, $fcm_token = '', $uid = '') {
        $token = self::generateToken($user);
        $user = UserRepository::authenticate($user, $token, $fcm_token, $uid);
        return $user;
    }

    public static function loginByEmail($user, $fcm_token = '', $password = '') {
        if($user == null) (new self)->sendFailedResponse([], "Sepertinya akun anda belum terdaftar");
        if(!Hash::check($password, $user->password)) {
            (new self)->sendFailedResponse([], "Maaf sepertinya password anda salah");       
        }
        $token = self::generateToken($user);
        $user = UserRepository::authenticate($user, $token, $fcm_token);
        return $user;
    }
    
    private static function generateToken($user) {
        $token = md5(request()->userAgent().env('API_KEY', ''));
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&=';
        $charactersLength = strlen($characters);
        $token .= '.';
        $salt = "";
        for ($i = 0; $i < 36; $i++) {
            $token .= $char = $characters[rand(0, $charactersLength - 1)];
            $salt .= $char;
        }

        return ['token'=>$token, 'salt'=>$salt];
    }
}
        