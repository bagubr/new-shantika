<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService {
    public static function login($user, $fcm_token = '') {
        $token = self::generateToken($user);
        $user = UserRepository::authenticate($user, $token, $fcm_token);
        return $user;
    }

    public static function refreshToken($user, $token, $refresh_token) {

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
        