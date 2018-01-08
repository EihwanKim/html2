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
        Commands\GetPrice::class,
        Commands\Buy::class,
        Commands\Send::class,
        Commands\Sell::class,
        Commands\Withdraw::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:get_price')
            ->everyMinute();
        if (env('APP_ENV') == 'production') {
            $schedule->command('command:buy')
                ->everyMinute();
            $schedule->command('command:send')
                ->everyMinute();
            $schedule->command('command:sell')
                ->everyMinute();
            $schedule->command('command:withdraw')
                ->hourly();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}