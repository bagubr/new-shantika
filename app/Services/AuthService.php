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
        if($user == null) (new self)->sendSuccessResponse([], $message = "Sepertinya akun anda belum terdaftar", $code = 401);
        $token = self::generateToken($user);
        $user = self::authenticate($user, $fcm_token, $uuid);
        return $user;
    }
    
    public static function loginByEmail($user, $fcm_token = '', $email = '',$uuid = '') {
        if($user == null) (new self)->sendSuccessResponse([], $message = "Sepertinya akun anda belum terdaftar", $code = 401);
        $user = self::authenticate($user, $fcm_token, $uuid);
        return $user;
    }

    public static function authenticate(User $user, $fcm_token = '', $uuid = '') {
        if(UserRepository::findUserIsAgent($user->id)) {
            $token = self::generateToken($user, false);
            $agent = request()->userAgent();
            UserToken::updateOrCreate([
                'user_id'=>$user->id,
                'user_agent'=>$agent
            ],[
                'token'=>$token
            ]);
            $user->update([
                'fcm_token'=>$fcm_token,
                'user_agent'=>$agent,
                'uuid'=>empty($uuid) ? $user->uuid : $uuid 
            ]);
        } else {
            $token = self::generateToken($user, true);
            $user->update([
                'uuid'=>empty($uuid) ? $user->uuid : $uuid,
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
        