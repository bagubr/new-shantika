<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\AuthController as BaseAuthController;
use App\Http\Requests\Api\ApiRegisterCustomerRequest;
use App\Http\Requests\Api\Auth\ApiLoginRequest;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends BaseAuthController
{
    public function loginPhone(ApiLoginRequest $request) {
        $user = UserRepository::findByPhone($request['phone'])
            ?? $this->sendFailedResponse([], $message = "Oops! Sepertinya anda belum pernah registrasi pake nomor ini", $code = 401);
        return $this->sendSuccessResponse([
            'user'=>AuthService::login($user, $request['fcm_token'], $request['phone'], $request['uuid']),
            'token'=>$user->token
        ]);
    }

    public function loginEmail(Request $request) {
        $user = UserRepository::findByEmail($request['email']) ?? $this->sendFailedResponse([], $message = "Oops! Sepertinya anda belum pernah registrasi pake nomor ini", $code = 401);
        return $this->sendSuccessResponse([
            'user'=>AuthService::loginByEmail($user, $request['fcm_token'], $request['email'], $request['uuid']),
            'token'=>$user->token
        ]);
    }
}