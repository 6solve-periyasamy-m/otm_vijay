<?php

namespace Tests\Traits;

use App\Models\User;

trait TestsAuthentication
{
    /**
     * @var User The user with all permissions
     */
    private User $user;

    /**
     * @var User The user with no permissions
     */
    private User $guest;

    public function setupAuthenticationRoles()
    {
        $this->user = User::factory()->create();
        $this->user->allow()->everything();
        $this->user->save();
        $this->guest = User::factory()->create();
        $this->guest->forbid()->everything();
        \Bouncer::refresh();
    }

    public function guest(): User
    {
        return $this->guest;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function userWithPermission($class, string $permission): User
    {
        $user = User::factory()->create();
        \Bouncer::allow($user)->to($permission, $class);
        $user->save();
        return $user;
    }

    public function generateUser(bool $guest = false): User
    {
        $user = User::factory()->create();
        if ($guest) {
            $user->forbid()->everything();
        } else {
            $user->allow()->everything();
        }
        $user->save();
        \Bouncer::refresh();
        return $user;
    }
}
