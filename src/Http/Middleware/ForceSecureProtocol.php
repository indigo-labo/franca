<?php

namespace IndigoLabo\Franca\Http\Middleware;

use Closure;

class ForceSecureProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!$request->secure() && config('secure_protocol.enabled')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
