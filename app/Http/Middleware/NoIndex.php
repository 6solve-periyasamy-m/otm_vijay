<?php

namespace App\Http\Middleware;

use Closure;

class NoIndex
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Add global noindex header
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}
