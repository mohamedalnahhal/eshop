<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class InitializeTenancyForLivewire
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $centralDomains = config('tenancy.central_domains', []);

        // If the current host is NOT a central domain, initialize tenancy
        if (! in_array($request->getHost(), $centralDomains)) {
            return app(InitializeTenancyByDomain::class)->handle($request, $next);
        }

        return $next($request);
    }
}