<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use \Silber\Bouncer\BouncerFacade as Bouncer;

class PermissionBouncer
{
    public function handle(Request $request, Closure $next, $model, $action) {
        if (Bouncer::can($action, app('\\App\\Models\\' . $model))) {
            return $next($request);
        }
        abort(403, 'You do not have permission to perform this action');
        return 1;
    }
}
