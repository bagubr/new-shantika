<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\UserController as BaseUserController;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends BaseUserController
{
    public function show(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken()) 
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');

        return $this->sendSuccessResponse([
            'user'=>$user
        ]);
    }

    public function update(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
        $user = UserService::updateCustomerProfile($user, $request->toArray());

        return $this->sendSuccessResponse([
            'user'=>$user
        ]);
    }
}
