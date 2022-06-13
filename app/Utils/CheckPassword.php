<?php

namespace App\Utils;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CheckPassword {
    public static function checkPassword($password)
    {
        $hashed = Auth::user()->password;
        if (!Hash::check($password, $hashed)) {
            session()->flash('error', 'Password anda tidak sama');
            return response([
                'code' => 0
            ]);
        }
    }
}