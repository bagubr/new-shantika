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

        return User::wherePhone($phone)->first() ?? false;
    }

    public static function findByToken($token)
    {
        if ($token) {
            $user = User::whereToken($token)->first();
            if (empty($user)) {
                $user_token = UserToken::where('token', $token)->first();
                $user = User::find($user_token->user_id);
            }
            if (empty($user)) {
                (new self)->sendFailedResponse([], "User doesn't exists");
            }
            return $user;
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
