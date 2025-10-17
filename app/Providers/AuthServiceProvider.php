<?php

namespace App\Providers;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('viewLogViewer', function (?User $user) {
           if (!isset($user) && (!$user instanceof User)) return false;

           if (!is_otm()) {
               return false;
           }

           return true;
        });

        foreach (['self-update', 'self-delete'] as $action) {
            Gate::define($action, function (User $user, $resource = null) use ($action) {
                return $this->checkSelfPermission($user, $resource, $action);
            });
        }

        /**
         * Dynamic gate for creating child resources.
         *
         * Usage in Blade:
         *   @can('self-child-access', [$parentModel, ChildModel::class])
         */
        Gate::define('self-child-access', function (User $user, $parent, string $childClass) {
            return $this->checkSelfChildPermission($user, $parent, $childClass);
        });

    }

    private function checkSelfPermission(User $user, $resource, string $action): bool
    {
        if (is_null($resource) || is_string($resource)) {
            return true;
        }

        if (method_exists($resource, 'getAttribute') && $resource->getAttribute('created_by') !== null) {

            if ($resource->created_by === $user->id) {
                return true;
            }

            $creator = User::find($resource->created_by);
            if ($creator && $this->hasSameRole($user, $creator)) {
                return true;
            }

            return false;
        }

        return false;
    }

    private function checkSelfChildPermission(User $user, $parent, string $childClass): bool
    {
        if (!$user->can('create', $childClass)) {
            return false;
        }

        if ($user->getHighestRoleLevel() > 5) {
            return true;
        }

        if ($parent->created_by === $user->id) {
            return true;
        }

        $creator = User::find($parent->created_by);
        if ($creator && $this->hasSameRole($user, $creator)) {
            return true;
        }
        return false;
    }

    /**
     * Check if two users have the same role
     */
    private function hasSameRole(User $user, User $creator): bool
    {
        $userRoles = $user->getRoles()->pluck('name')->toArray();
        $creatorRoles = $creator->getRoles()->pluck('name')->toArray();
        return !empty(array_intersect($userRoles, $creatorRoles));
    }


}
