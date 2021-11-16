<?php

namespace App\Console\Commands;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RegenerateAllApiTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-token:regenerate-all {expiry?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate all user tokens';

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
        $expiry = $this->argument('expiry') ?? ApiToken::DEFAULT_EXPIRY;
        foreach (User::all() as $user) {
            $user->generateToken($expiry);
        }
        return 0;
    }
}
