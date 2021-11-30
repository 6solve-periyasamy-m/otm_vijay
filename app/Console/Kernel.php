<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('api-token:regenerate-all')->hourlyAt(0); // Regenerate all API tokens once an hour
        $schedule->command('api-token:purge-old')->dailyAt('00:00'); // Automatically purge API tokens older than 2 days
        $schedule->command('countries:update')->weeklyOn(0);
        $schedule->command('payments:remind')->dailyAt('00:00'); // Send payment reminders at midnight evey day
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
