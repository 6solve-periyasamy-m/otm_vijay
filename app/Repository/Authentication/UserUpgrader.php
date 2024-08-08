<?php

namespace App\Repository\Authentication;

use App\Models\User;
use Database\Seeders\UserSeeder;

class UserUpgrader
{
    public function run_upgrades()
    {
        $this->upgrade_1_to_2();
        $this->setupDefaultUsers(); // Added Nicholas Alexander as a default user. No users left to remove
    }

    public function upgrade_1_to_2(): void
    {
        // Remove Charlotte Redding
        $this->removeUser('clr@octopustravelmatrix.com');
        // Remove Steve Eatherington
        $this->removeUser('sae@octopustravelmatrix.com');

        $this->setupDefaultUsers();
    }

    private function removeUser(string $email): void
    {
        User::where('email', '=', $email)->first()?->forceDelete();
    }

    private function updateUser(User $newUser): void
    {
        $user = User::where('email', '=', $newUser->email)->first();
        if ($user !== null) {
            $user->update($newUser->toArray());
            $user->assign('otm-staff');
            $user->save();
        } else {
            $newUser->save();
            $newUser->assign('otm-staff');
        }
    }

    private function setupDefaultUsers(): void
    {
        $seeder = new UserSeeder();
        foreach ($seeder->defaultUsers() as $user) {
            $this->updateUser($user);
        }
        if (config('app.debug')) {
            foreach ($seeder->demoUsers() as $user) {
                $this->updateUser($user);
            }
        }
    }
}
