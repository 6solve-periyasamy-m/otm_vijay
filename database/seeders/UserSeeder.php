<?php

namespace Database\Seeders;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \Silber\Bouncer\BouncerFacade as Bouncer;

class UserSeeder extends Seeder
{

    public function run() {
        Bouncer::allow('otm-staff')->everything();
        Bouncer::allow('administrator')->everything();
        Bouncer::forbid('administrator')->to('promote-to-administrator');
        Bouncer::allow('user')->everything();
        Bouncer::forbid('user')->toManage(User::class);
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
            'password' => Hash::make('password'),
        ]);
        $celeste->assign('otm-staff');
        $charlotte->assign('otm-staff');
        $nicholas->assign('otm-staff');

    }
}
