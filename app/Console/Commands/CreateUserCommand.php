<?php

namespace App\Console\Commands;

use App\Models\User;
use Hash;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {--otm}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an administrator on the system. Use the --otm flag to create a superuser';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (config('app.env') !== 'local') {
            $this->error('This command cannot be run on production servers');
        }
        $group = $this->option('otm') ? 'otm-staff' : 'administrator';
        $name = $this->ask('Full Name: ');
        $email = $this->ask('Email Address: ');
        while (true) {
            $password = $this->secret('Password: ');
            if (strlen($password) < 5) {
                $this->error('Password must be at least 5 characters');
                continue;
            }
            if ($password === $this->secret('Confirm Password: ')) {
                break;
            }
            $this->error('The passwords do not match');
        }
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $user->assign($group);
        $this->info('User Created Successfully!');
        return self::SUCCESS;
    }
}
