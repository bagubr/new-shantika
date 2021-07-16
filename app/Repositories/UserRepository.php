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

    public static function findByEmail($email) {
        return User::where('email', $email)->first();
    }

    public static function getAll() {
        return User::all();
    }

    public static function authenticate(User $user, $token, $fcm_token = '', $uuid = '') { 
        $user->update([
            'token'=>$token['token'],
            'fcm_token'=>$fcm_token,
            'uuid'=>$uuid
        ]);
        return $user;
    }

    public static function findByTokenAndRefreshToken($token, $refresh_token) {
        return User::whereToken($token)->whereRefreshToken($refresh_token)->first();
    }
}