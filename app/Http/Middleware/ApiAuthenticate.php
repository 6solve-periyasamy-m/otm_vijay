<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ApiAuthenticate
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
        if (!$request->has('__api_token')) {
            abort(403, 'API Token is Required');
        }
        try {
            $token = ApiToken::findOrFail($request->input('__api_token'));
            if ($token->hasExpired()) {
                abort(403, 'API Token is either not recognized or expired');
            }
        } catch (ModelNotFoundException $ignored) {
            abort(403, 'API Token is either not recognized or expired');
        }
        return $next($request);
    }
}
