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
    public function checkUuid(Request $request)
    {
        if($request['phone']){
            $user = UserRepository::findByPhone($request['phone']);
        }else{
            $user = UserRepository::findByEmail($request['email']);
        }
        if(!empty($request['uuid']) && $user->uuid != $request['uuid']) {    
            $this->sendFailedResponse([], $message = "Oops! Uuid doesn't match", $code = 401);
        }else{
            $this->sendSuccessResponse([
                'user' => $user,
                'token'=>$user->token,
            ], $message = "Uuid Matched !!!", $code = 200);
        }
    }
}