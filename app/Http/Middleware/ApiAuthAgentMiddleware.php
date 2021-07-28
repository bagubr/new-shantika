<?php

namespace App\Http\Middleware;

use App\Models\UserAgent;
use App\Repositories\UserRepository;
use App\Utils\Response;
use Closure;
use Illuminate\Http\Request;

class ApiAuthAgentMiddleware
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
        //Check if agent
        $agent = UserAgent::where('user_id', $user->id)->first();
        if(empty($agent)) $this->sendFailedResponse([], 'Oops, kamu pakai akun customer untuk mengakses aplikasi agen?!', 401);
        
        return $next($request);
    }
}
