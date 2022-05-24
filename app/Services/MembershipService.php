<?php

namespace App\Services;

use App\Models\Membership;
use App\Models\MembershipPoint;
use App\Models\User;

class MembershipService {
    public static function create($user_id)
    {
        $user = User::find($user_id);
        if(isset($user->membership->id)){
            $membership =  $user->membership;   
        }else{
            $membership = Membership::create([
                'user_id' => $user->id,
                'name' => $user->name, 
                'phone' => $user->phone, 
                'address' => $user->address
            ]);
        }
        return $membership;
    }

    public static function decrement(Membership $membership, $point, $message = '')
    {
        $membership->decrement('sum_point', $point);
        MembershipPoint::create([
            'membership_id' => $membership->id,
            'value' => $point,
            'status' => 0,
            'message' => $message
        ]);
    }

    public static function increment(Membership $membership, $point, $message = '')
    {
        $membership->decrement('sum_point', $point);
        MembershipPoint::create([
            'membership_id' => $membership->id,
            'value' => $point,
            'status' => 0,
            'message' => $message
        ]);
    }
}
        