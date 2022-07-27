<?php

namespace App\Console;

use App\Models\Tool;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\Subscriptions\TrialEndNotification;

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

        $schedule->call(function () {
            Tool::remindToInventory();
            Tool::remindEvery3WeeksAboutToolsInService();
        })->dailyAt('10:00');

        $schedule->command(TrialEndNotification::class)->dailyAt('06:00');// For trials ending in 3 days and on last day
        $schedule->command(TrialEndNotification::class, [1])->everyMinute();// For trials ended on time
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
