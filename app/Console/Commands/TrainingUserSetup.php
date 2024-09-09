<?php

namespace App\Console\Commands;

use App\Models\User;
use Hash;
use Illuminate\Console\Command;

class TrainingUserSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'training:setup {--delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable/Disable the training user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $username = 'training@octopustravelmatrix.com';
        $password = 'password';
        $user = User::where('email', '=', $username)->first();
        if ($this->option('delete')) {
            if ($user !== null) {
                $user->forceDelete();
                $this->info('Training user has been removed');
            } else {
                $this->error('Training user is not enabled');
            }
            return 0;
        }

        if ($user === null) {
            $user = User::create([
                'name' => 'Training User',
                'email' => $username,
                'password' => Hash::make($password),
            ]);
            $user->assign('otm-staff');
            $this->info("Training user has been enabled!\nUser: $username\nPassword: $password");
        } else {
            $this->error('Training user is already enabled');
        }
        return 0;
    }
}
