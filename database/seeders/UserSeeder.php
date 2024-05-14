<?php

namespace Database\Seeders;
use App\Models\System\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserSeeder extends Seeder
{

    public function run() {
        $otmStaff = Bouncer::role()->firstOrCreate([
            'name' => 'otm-staff',
            'title' => 'OTM Staff Member',
            'level' => 999,
        ]);
        Bouncer::allow($otmStaff)->everything();

        $administrator = Bouncer::role()->firstOrCreate([
            'name' => 'administrator',
            'title' => 'Administrator',
            'level' => 100,
        ]);
        Bouncer::allow($administrator)->everything();

        $userRole = Bouncer::role()->firstOrCreate([
            'name' => 'user',
            'title' => 'Staff',
            'level' => 5,
        ]);
        Bouncer::allow($userRole)->everything();
        Bouncer::forbid($userRole)->toManage(User::class);
        Bouncer::forbid($userRole)->toManage(Setting::class);

        $guest = Bouncer::role()->firstOrCreate([
           'name' => 'guest',
           'title' => 'Guest',
           'level' => 0
        ]);

        $jon = User::create([
            'name' => 'Jon Redding',
            'email' => 'jsr@octopustravelmatrix.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$9sAbr6xuyq.blFnbTk/iBeA1UURkdLbLy3las/sf18RUGRKgG8XcO',
        ]);

        $celeste = User::create([
            'name' => 'Celeste Gateley',
            'email' => 'celeste@octopustravelmatrix.com',
            'email_verified_at' => now(),
            'password' => '$2a$10$9MlxA3x/35CcJgQvkEXMqeSLs/pzDup2F1LYwwyLoexJisg1rr.WG',
        ]);

        $jon->assign($otmStaff);
        $celeste->assign($otmStaff);

        if (config('app.debug')) {
            $nisha = User::create([
                'name' => 'Nisha Bajaj',
                'email' => 'nisha.bajaj@kpt.com.au',
                'email_verified_at' => now(),
                'password' => '$2y$10$BjbO0lpgLYrY3LNWCnrADOo4AXgOPjTXV1sW9F/2KgABZnRCBbAXO',
            ]);

            $nisha->assign($otmStaff);
        }
    }
}
