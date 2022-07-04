<?php

namespace App\Repositories;


use App\Models\User;
use App\Services\MembershipService;

class MembershipRepository {
    
    public static function getByUserId($user_id = null)
    {
        $user = User::has('membership')->where('id', $user_id)->first();
        if(!$user){
            return MembershipService::create($user_id);
        }
        return $user->membership;
    }
}
