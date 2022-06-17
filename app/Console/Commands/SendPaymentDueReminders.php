<?php

namespace App\Console\Commands;

use App\Models\Order\Order;
use Illuminate\Console\Command;
use Settings;

class SendPaymentDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:remind {days?} {min?} {--force}';

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
        if (Settings::authorized('authorization.reminders') || $this->hasOption('force')) {
            $days = $this->argument('days') ?? 7;
            $min = $this->argument('min') ?? -1000;
            foreach (Order::where('cancelled',false)->get() as $order) {
                $order->repository->sendReminderEmails($days, $min);
            }
        } else {
            $this->error('Reminders are not authorized to run. Use --force to bypass this check');
        }
        return 0;
    }
}
