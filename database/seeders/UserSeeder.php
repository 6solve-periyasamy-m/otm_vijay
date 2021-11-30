<?php

namespace Database\Seeders;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \Silber\Bouncer\BouncerFacade as Bouncer;
use Silber\Bouncer\Database\Role;

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

        $celeste->assign($otmStaff);
        $charlotte->assign($otmStaff);
        $nicholas->assign($otmStaff);
    }
}
