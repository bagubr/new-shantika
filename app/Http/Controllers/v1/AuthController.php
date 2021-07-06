<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiLoginRequest;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(ApiLoginRequest $request) {
        $user = UserRepository::findByPhone($request['phone']);

        return $this->sendSuccessResponse([
            'user'=>AuthService::login($user, $request['fcm_token']),
            'token'=>$user->token
        ]);
    }
}