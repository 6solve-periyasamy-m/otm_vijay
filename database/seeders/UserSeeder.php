<?php

namespace Database\Seeders;
use App\Models\System\Setting;
use App\Models\System\User;
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

        $charlotte = User::create([
            'name' => 'Charlotte Redding',
            'email' => 'clr@octopustravelmatrix.com',
            'email_verified_at' => now(),
            'password' => '$2a$10$1TEFiRVsIG3R9Aa9JiJ1cuDSVqLffb9J11HSmHO5oIDliHaunqd8C',
        ]);

        $charlotte->assign($otmStaff);

        if (config('app.debug')) {
            $celeste = User::create([
                'name' => 'Celeste Gateley',
                'email' => 'celeste@octopustravelmatrix.com',
                'email_verified_at' => now(),
                'password' => '$2a$10$aqDFZNjT0To9Vsrs.edpK.i6K4JVrrjRGfKjj7TQMrDyCoEf5FSRO',
            ]);
            $nicholas = User::create([
                'name' => 'Nicholas Alexander',
                'email' => 'work@sfsw.net',
                'email_verified_at' => now(),
                'password' => '$2a$12$cZrltF34KgJtI0V0Ob8nm.osVJavm4lvo.4E2vjol2iC652B.f2Oy',
            ]);
            $steveEatherington = User::create([
                'name' => 'Steve Eatherington',
                'email' => 'sae@octopustravelmatrix.com',
                'email_verified_at' => now(),
                'password' => '$2a$12$t8H9Tx2lPdlQuJVbsFGEmelKuma6KAk4SfMJSo0S3L1HWZYKiENsS',
            ]);

            $celeste->assign($otmStaff);
            $nicholas->assign($otmStaff);
            $steveEatherington->assign($otmStaff);
        }
    }
}
