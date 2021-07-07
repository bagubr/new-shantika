<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository {
    public static function findByPhone($phone) {
        return User::wherePhone($phone)->first();
    }

    public static function findByToken($token) {
        return User::whereToken($token)->first();
    }

    public static function authenticate(User|null $user, $token, $fcm_token = '', $phone = '') { 
        if($user == null) {
            $user = self::findByPhone($phone);
        }
        $user->update([
            'token'=>$token['token'],
            'fcm_token'=>'dsadsa'
        ]);
        return $user;
    }

    public static function findByTokenAndRefreshToken($token, $refresh_token) {
        return User::whereToken($token)->whereRefreshToken($refresh_token)->first();
    }
}