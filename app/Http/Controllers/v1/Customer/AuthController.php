<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\AuthController as BaseAuthController;
use App\Http\Requests\Api\ApiRegisterCustomerRequest;
use App\Http\Requests\Api\Auth\ApiLoginRequest;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\DB;
>>>>>>> rilisv1

class AuthController extends BaseAuthController
{
    public function loginPhone(ApiLoginRequest $request) {
        $user = UserRepository::findCostumerByPhone($request['phone'])
            ?? $this->sendSuccessResponse([], $message = "Sepertinya akun anda belum terdaftar", $code = 401);
        $token = AuthService::login($user, 'CUSTOMER', $request['fcm_token'], $request['phone'], $request['uuid']);
        return $this->sendSuccessResponse([
            'user'=>$user,
            'token'=>$token
        ]);
    }

    public function loginEmail(Request $request) {
        $user = UserRepository::findCostumerByEmail($request['email']) ?? $this->sendSuccessResponse([], $message = "Sepertinya akun anda belum terdaftar", $code = 401);
        $token = AuthService::loginByEmail($user, 'CUSTOMER', $request['fcm_token'], $request['email'], $request['uuid']);
        return $this->sendSuccessResponse([
            'user'=>$user,
            'token'=>$token
        ]);
    }

    public function registerCustomer(ApiRegisterCustomerRequest $request) {
<<<<<<< HEAD
        $user = UserService::register($request->all(), $request->order_id??[]);
=======
        DB::beginTransaction();
        try {
            $user = UserService::register($request->all(), $request->order_id??[]);
            DB::commit();
        } catch (\Throwable $th) {
            return $this->failedResponse([], 'Something error , please try again latter');
            DB::rollBack();
        }
>>>>>>> rilisv1
        return $this->sendSuccessResponse([
            'user'=>$user,
            'token'=>$user->token??''
        ]);
    }

}