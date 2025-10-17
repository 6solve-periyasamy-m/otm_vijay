<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;
use App\Repository\Authentication\PermissionsRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PermissionBouncer
{
    public function handle(Request $request, Closure $next, $model, $actions)
    {
        $fqcn = 'App\\Models\\' . $model;

        if (!class_exists($fqcn)) {
            abort(404, "Model {$model} not found.");
        }

        $actions = explode('|', $actions);
        $user = $request->user();        
        foreach ($actions as $action) {
            // Handle self permissions
            if (Str::startsWith($action, 'self-')) {
                if ($this->hasSelfPermission($user, $model, $action, $request)) {
                    return $next($request);
                }
                continue;
            }
            // Handle general permissions
            if ($user->can($action, $fqcn)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to perform this action.');
    }

    protected function hasSelfPermission(User $user, string $model, string $action, Request $request): bool
    {
        $instance = $this->getModelInstanceFromRoute($request, $model);        
        if (!$instance) {
            return false;
        }

        // For other self permissions, check ownership
        if ($instance->created_by === $user->id) {
            return true;
        }

        // Check if user has same role as creator
        $creator = User::find($instance->created_by);
        if ($creator && $this->hasEqualOrHigherRoleLevel($user, $creator)) {
            return true;
        }
        return false;
    }

    protected function getModelInstanceFromRoute(Request $request, string $model)
    {
        return collect($request->route()->parameters())
            ->first(fn($param) => $param instanceof \Illuminate\Database\Eloquent\Model
                && get_class($param) === "App\\Models\\{$model}");
    }

    private function hasEqualOrHigherRoleLevel(User $currentUser, User $creatorUser): bool
    {
        $currentUserRoleLevel = $currentUser->getHighestRoleLevel();
        $creatorRoleLevel = $creatorUser->getHighestRoleLevel();
        return $currentUserRoleLevel >= $creatorRoleLevel;
    }

}
