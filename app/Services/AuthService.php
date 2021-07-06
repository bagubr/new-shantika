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
    
    private static function generateToken($user) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&=';
        $charactersLength = strlen($characters);
        $token = '';
        for ($i = 0; $i < 257; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        return $token;
    }
}
        