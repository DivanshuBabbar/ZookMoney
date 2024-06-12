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
        $schedule->command('admin:restore')
                  ->everyFiveMinutes();
        $schedule->command('ledger:report')->dailyAt('08:00')->when(function () {
            return \Carbon\Carbon::now()->endOfMonth()->isToday();
        });
        $schedule->command('payout:report')->dailyAt('08:05')->when(function () {
            return \Carbon\Carbon::now()->endOfMonth()->isToday();
        });
        $schedule->command('ledgerDaily:report')->dailyAt('01:00');
        $schedule->command('payoutDaily:report')->dailyAt('01:00');
       
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
