<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        if($request->header('X-API-KEY') != env('API_KEY') && env('APP_DEBUG') == false) {
            return abort(403, 'Invalid API Key');
        }
        return $next($request);
    }
}
