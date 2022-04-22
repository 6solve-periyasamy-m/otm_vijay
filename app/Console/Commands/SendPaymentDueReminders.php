<?php

namespace App\Console\Commands;

use App\Repository\OrderRepository;
use Illuminate\Console\Command;

class SendPaymentDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:remind {days?} {min?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder to all customers who have not received their 7 day reminder';

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
        $days = $this->argument('days') ?? 7;
        $min = $this->argument('min') ?? -1000;
        OrderRepository::sendAllOrderReminders($days, $min);
        return 0;
    }
}
