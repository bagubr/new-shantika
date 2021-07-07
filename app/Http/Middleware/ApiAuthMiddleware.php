<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use App\Utils\Response;
use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    use Response;
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
        $user = UserRepository::findByToken($token);
        //Check token
        if(!str_contains($user->token, $token)) {
            $this->sendFailedResponse([], "Oops, anda sepertinya harus login ulang",401);
        }
        //Check device
        $agent = $request->userAgent();
        $serialized = md5($agent.env('API_KEY', ''));
        if(!str_contains($user->token, $serialized)) {
            $this->sendFailedResponse([], "Oops, anda sepertinya harus login ulang",401);
        }
        
        return $next($request);
    }
}
