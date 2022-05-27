<?php

namespace App\Http\Middleware;

use App\Models\UserAgent;
use App\Models\UserToken;
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
        $user = UserToken::where('token', $token)->exists() ?? $this->sendFailedResponse([], "Oops, anda sepertinya harus login ulang", 401);
        if($user) {
            return $next($request);

        }
        return $this->sendFailedResponse([], 'Oops, sepertinya anda harus login ulang');
    }
}
