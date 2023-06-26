<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Used to override any config values that need to be set dynamically
 */
class ConfigOverride
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $dateFormat = setting('system.format.date', 'd/m/Y');
        $timeFormat = setting('system.format.time', 'H:i');
        Config::set('livewire-datatables.default_date_format', $dateFormat);
        Config::set('livewire-datatables.default_time_format', $timeFormat);
        Config::set('livewire-datatables.default_datetime_format', "$dateFormat $timeFormat");
        return $next($request);
    }
}
