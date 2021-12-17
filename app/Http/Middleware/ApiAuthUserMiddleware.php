<?php

namespace App\Http\Middleware;

use App\Models\UserAgent;
use App\Repositories\UserRepository;
use App\Utils\Response;
use Closure;
use Illuminate\Http\Request;

class ApiAuthUserMiddleware
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
        $user = UserRepository::findByToken($token) ?? $this->sendFailedResponse([], "Oops, anda sepertinya harus login ulang", 401);
        //Check token
        if(!str_contains($user->token, $token)) {
            $this->sendFailedResponse([], "Oops, anda sepertinya harus login ulang",401);
        }
        //Check if not agent
        $agent = UserAgent::where('user_id', $user->id)->first();
        if($agent) $this->sendFailedResponse([], 'Oops, kamu pakai akun agent untuk mengakses aplikasi customer?!', 401);
        //Check device
        $user_agent = $request->userAgent();
        $serialized = md5($user_agent.env('API_KEY', ''));
        if(!str_contains($user->token, $serialized)) {
            $this->sendFailedResponse([], "Oops, anda sepertinya harus login ulang",401);
        }
        
        return $next($request);
    }
}
