<?php

namespace App\Console\Commands;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Console\Command;

class PurgeOldApiTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-token:purge-old {hours?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete api tokens older that a certain number of days';

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
        $limit = $this->argument('hours') ?? ApiToken::DEFAULT_LIMIT;
        foreach (User::all() as $user) {
            $user->purgeTokens($limit);
        }
        return 0;
    }
}
