<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository {
    public static function findByPhone($phone) {
        return User::wherePhone($phone)->first();
    }

    public static function authenticate(User|null $user, $token = '', $fcm_token = '', $phone = '') { 
        if($user == null) {
            $user = self::findByPhone($phone);
        }
        return $user->update(compact('token', 'fcm_token'));
    }
}