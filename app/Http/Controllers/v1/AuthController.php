<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiRegisterCustomerRequest;
use App\Http\Requests\Api\Auth\ApiLoginRequest;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginPhone(ApiLoginRequest $request) {
        $user = UserRepository::findByPhone($request['phone'])
            ?? $this->sendFailedResponse([], "Oops! Sepertinya anda belum pernah registrasi pake nomor ini");

        return $this->sendSuccessResponse([
            'user'=>AuthService::login($user, $request['fcm_token']),
            'token'=>$user->token
        ]);
    }
    
    public function loginUid(Request $request) {
        $user = UserRepository::findByPhone($request['phone']) ?? $this->sendFailedResponse([], "Oops! Sepertinya anda belum pernah registrasi pake nomor ini");

        return $this->sendSuccessResponse([
            'user'=>AuthService::login($user, $request['fcm_token'], $request['phone'], $request['uid']),
            'token'=>$user->token
        ]);
    }

    public function loginEmail(Request $request) {
        $user = UserRepository::findByEmail($request['email']);

        return $this->sendSuccessResponse([
            'user'=>AuthService::loginByEmail($user, $request['fcm_token'], $request['password']),
            'token'=>$user->token
        ]);
    }

    public function registerCustomer(ApiRegisterCustomerRequest $request) {
        $user = UserService::register($request->all());          

        return $this->sendSuccessResponse([
            'user'=>$user,
            'token'=>$user->token
        ]);
    }
}