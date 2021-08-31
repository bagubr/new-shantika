<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserAgent;
use App\Models\UserToken;
use App\Utils\Response;

class UserRepository
{
    use Response;
    public static function findByPhone($phone)
    {
        return User::with('agencies.agent')->wherePhone($phone)->first();
    }

    public static function findAgentByPhone($phone)
    {
        return User::has('agencies')->with('agencies.agent')->wherePhone($phone)->first();
    }

    public static function findAgentByEmail($email) {
        return User::has('agencies')->where('email', $email)->first();
    }

    public static function findCostumerByPhone($phone) {
        return User::whereDoesntHave('agencies')->where('phone', $phone)->first();
    }

    public static function findCostumerByEmail($email) {
        return User::whereDoesntHave('agencies')->where('email', $email)->first();
    }

    public static function findByToken($token)
    {
        try {
            if ($token) {
                $user = User::whereToken($token)->first();
                if (empty($user)) {
                    $user_token = UserToken::where('token', $token)->first() 
                        ?? (new self)->sendFailedResponse([], "Oops sepertinya anda harus login ulang");
                    $user = User::with('agencies.agent.city')->find($user_token?->user_id);
                }
                if (empty($user)) {
                    (new self)->sendFailedResponse([], "Oops sepertinya anda harus login ulang");
                }
                return $user;
            }
        } catch (\Throwable $th) {
            (new self)->sendFailedResponse([], "Oops, sepertinya anda harus login ulang");
        }
    }

    public static function findByEmail($email)
    {
        return User::where('email', $email)->first() ?? false;
    }

    public static function getAll()
    {
        return User::all();
    }

    public static function notAgent()
    {
        return User::doesntHave('agencies')->doesntHave('membership')->get(['id', 'name']);
    }
    public static function notAgentMember()
    {
        return User::doesntHave('agencies')->get(['id', 'name']);
    }

    public static function authenticate(User $user, $token, $fcm_token = '', $uuid = '')
    {
        $user->update([
            'token' => $token['token'],
            'fcm_token' => $fcm_token,
            'uuid' => $uuid
        ]);
        return $user;
    }

    public static function findByTokenAndRefreshToken($token, $refresh_token)
    {
        return User::whereToken($token)->whereRefreshToken($refresh_token)->first();
    }

    public static function findUserIsAgent($user_id)
    {
        return UserAgent::whereUserId($user_id)->first() ? true : false;
    }
}
