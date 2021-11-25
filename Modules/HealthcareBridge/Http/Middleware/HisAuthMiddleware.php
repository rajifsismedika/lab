<?php

namespace Modules\HealthcareBridge\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HisAuthMiddleware
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
        if ($request->header('Authorization') != env('HIS_AUTH_KEY')) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
