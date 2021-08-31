<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken() ?? $this->sendFailedResponse([], "Oops, anda sepertinya harus login ulang", 401);
        $user = UserRepository::findByToken($token) ?? $this->sendFailedResponse([], "Oops, anda sepertinya harus login ulang", 401);
        //Check token
        if(!str_contains($user->token, $token)) {
            return $this->sendFailedResponse([], "Oops, anda sepertinya harus login ulang",401);
        }
        if($user->is_active == false ){
            return $this->sendFailedResponse([], "Oops, akun anda telah dinonaktifkan",401);
        }
        return $next($request);
    }
}
