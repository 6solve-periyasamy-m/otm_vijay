<?php

use App\Models\User;

if (!function_exists('is_otm')) {
    function is_otm(): bool
    {
        $user = get_current_admin();
        if (empty($user) || !($user instanceof User)) return false;
        return $user->getHighestRoleLevel() >= 999;
    }
}
if (!function_exists('get_current_admin')) {
    /**
     * Get the currently logged in admin user, or null
     * @return User|null
     */
    function get_current_admin(): User|null
    {
        $user = Auth::guard('web')->user();
        if ($user instanceof User) return $user;
        return null;
    }
}
