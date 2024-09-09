<?php

namespace App\Http\Middleware;

use App\Models\System\ApiToken;
use App\Models\System\CustomerApiToken;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DualApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->has('__api_token')) {
            abort(403, 'API Token is Required');
        }
        try {
            $token = ApiToken::find($request->input('__api_token'));
            if (!isset($token)) {
                $token = CustomerApiToken::find($request->input('__api_token'));
            }
            if (!isset($token) || $token->hasExpired()) {
                abort(403, 'API Token is either not recognized or expired');
            }
        } catch (ModelNotFoundException $ignored) {
            abort(403, 'API Token is either not recognized or expired');
        }
        return $next($request);
    }
}
