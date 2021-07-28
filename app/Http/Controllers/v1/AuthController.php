<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiRegisterCustomerRequest;
use App\Http\Requests\Api\Auth\ApiLoginRequest;
use App\Models\UserToken;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function checkUuid(Request $request)
    {
        $user = UserRepository::findByPhone($request['phone']);
        if($user){
            if(!empty($request['uuid']) && $user->uuid != $request['uuid']) {    
                $this->sendSuccessResponse([], $message = "Oops! Uuid doesn't match", $code = 401);
            }else{
                if(UserRepository::findUserIsAgent($user->id)) {
                    $user_token = UserToken::where('user_id', $user->id)->where('user_agent', $request->userAgent())->first();
                    if(empty($user_token?->token)) {
                        $this->sendFailedResponse([], 'Oops anda harus login ulang', 401);
                    }
                    $this->sendSuccessResponse([
                        'user' => $user,
                        'token'=>$user_token->token,
                    ], $message = "Uuid Matched !!!", $code = 200);
                }
                else {
                    $this->sendSuccessResponse([
                        'user' => $user,
                        'token'=>$user->token,
                    ], $message = "Uuid Matched !!!", $code = 200);
                }
            }
        }
        $this->sendFailedResponse([], $message = "Oops! User doesn't exist", $code = 404);
    }
}