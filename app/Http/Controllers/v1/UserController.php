<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showProfileCustomer(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken()) 
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');

        return $this->sendSuccessResponse([
            'user'=>$user
        ]);
    }

    public function updateProfileCustomer(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
        $user = UserService::updateCustomerProfile($user, $request->toArray());

        return $this->sendSuccessResponse([
            'user'=>$user
        ]);
    }
}
