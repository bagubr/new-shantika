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
        $user = UserRepository::findAgentByPhone($request['phone'])
            ?? $this->sendFailedResponse([], $message = "Oops! Sepertinya anda belum pernah registrasi pake nomor ini", $code = 401);
        $token = AuthService::login($user, 'AGENT', $request['fcm_token'], $request['phone'], $request['uuid']);
        return $this->sendSuccessResponse([
            'user'=>$user,
            'token'=>$token
        ]);
    }

    public function loginEmail(Request $request) {
        $user = UserRepository::findByEmail($request['email']) ?? $this->sendFailedResponse([], $message = "Oops! Sepertinya anda belum pernah registrasi pake email ini", $code = 401);
        $token = AuthService::loginByEmail($user, 'AGENT', $request['fcm_token'], $request['email']);
        return $this->sendSuccessResponse([
            'user'=>$user,
            'token'=>$token
        ]);
    }
}