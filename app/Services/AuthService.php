<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserToken;
use App\Repositories\UserRepository;
use App\Utils\Response;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthService {
    use Response;

    public static function login($user, string $type, $fcm_token = '', $phone = '',$uuid = '') {
        if(!in_array($type, ['AGENT', 'CUSTOMER'])) {
            throw new Exception('Unsupported role login');
        }
        if($user == null) (new self)->sendSuccessResponse([], $message = "Sepertinya akun anda belum terdaftar", $code = 401);
        $user = self::authenticate($user, $type, $fcm_token, $uuid);
        return $user;
    }
    
    public static function loginByEmail($user, string $type, $fcm_token = '', $email = '',$uuid = '') {
        if(!in_array($type, ['AGENT', 'CUSTOMER'])) {
            throw new Exception('Unsupported role login');
        }
        if($user == null) (new self)->sendSuccessResponse([], $message = "Sepertinya akun anda belum terdaftar", $code = 401);
        $user = self::authenticate($user, $type, $fcm_token, $uuid);
        return $user;
    }

    public static function authenticate(User $user, $type, $fcm_token = '', $uuid = '') {
        $is_agent = UserRepository::findUserIsAgent($user->id);
        if( $is_agent && $type == 'AGENT') {
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
        } elseif(!$is_agent && $type == 'CUSTOMER') {
            $token = self::generateToken($user, true);
            $user->update([
                'uuid'=>empty($uuid) ? $user->uuid : $uuid,
                'token'=>$token,
                'fcm_token'=>$fcm_token
            ]);
        } else {
            (new self)->sendFailedResponse([], 'Nomor anda telah terdaftar di akun lain');
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
        