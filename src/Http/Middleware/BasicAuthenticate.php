<?php

namespace IndigoLabo\Franca\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\IpUtils;

class BasicAuthenticate
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     *
     * @return ResponseFactory|Response|mixed
     */
    public function handle($request, $next)
    {
        if (!config('basic_auth.enabled')) {
            return $next($request);
        }
        if ($this->isAuthUsername($request) && $this->isAuthPassword($request)) {
            return $next($request);
        }
        if (IpUtils::checkIp($request->ip(), array_filter(config('basic_auth.approved_ips', []), "strlen"))) {
            return $next($request);
        }

        $header = ['WWW-Authenticate' => 'Basic realm="Access denied", charset="UTF-8"'];
        return response('You have to supply your credentials to access this resource.', 401, $header);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    private function isAuthUsername($request): bool
    {
        return config('basic_auth.username') === $request->getUser();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    private function isAuthPassword($request): bool
    {
        return config('basic_auth.password') === $request->getPassword();
    }
}
