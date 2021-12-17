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
        $user = $this->getUser($request->type, $request->phone);
        if(empty($user)){
            $this->sendSuccessResponse([], $message = "Sepertinya akun anda belum terdaftar", $code = 401);
        }
        if(!empty($request['uuid']) && $user->uuid != $request['uuid']) {    
            $this->sendSuccessResponse([], $message = "Oops! Uuid doesn't match", $code = 401);
        }
        if(UserRepository::findUserIsAgent($user->id) && $request->type == 'AGENT') {
            $this->sendSuccessResponse([
                'user' => $user,
                'token'=>$user->token,
            ], $message = "Uuid Matched !!!", $code = 200);
        }
        $user_token = UserToken::where('user_id', $user->id)->where('user_agent', $request->userAgent())->first();
        if(empty($user_token?->token)) {
            $this->sendSuccessResponse([], 'Oops anda harus login ulang', 401);
        }
        $this->sendSuccessResponse([
            'user' => $user,
            'token'=>$user_token->token,
        ], $message = "Uuid Matched !!!", $code = 200);
    }

    private function getUser($type, $phone) {
        if($type == 'AGENT') {
            $user = UserRepository::findAgentByPhone($phone);
        } elseif ($type == 'CUSTOMER') {
            $user = UserRepository::findCostumerByPhone($phone);
        } else {
            $user = UserRepository::findAgentByPhone($phone);
        }
        return $user;
    }
}