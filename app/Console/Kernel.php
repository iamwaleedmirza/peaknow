<?php

namespace App\Console;

use App\Console\Commands\CheckForExpiredOrders;
use App\Console\Commands\GetOrderShipments;
use App\Console\Commands\GetPrescriptions;
use App\Console\Commands\HandleOrderSubscriptions;
use App\Console\Commands\OrderStatusUpdate;
use App\Console\Commands\GetPrescriptionsByLibertyScriptNumber;
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
        Commands\SetScheduler::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (app()->environment(['local', 'staging'])) {
            $schedule->command(GetPrescriptions::class)->everyMinute();
            $schedule->command(GetOrderShipments::class)->everyMinute();
            $schedule->command(GetPrescriptionsByLibertyScriptNumber::class)->everyMinute();
            $schedule->command('call:SetScheduler')->everyMinute();
            $schedule->command('auth:clear-resets')->everyMinute();
            $schedule->command(CheckForExpiredOrders::class)->everyMinute();
            $schedule->command(HandleOrderSubscriptions::class)->everyMinute();
            $schedule->command(OrderStatusUpdate::class)->everyMinute();
        } else {
            $schedule->command('call:SetScheduler')->everyMinute();
            $schedule->command('auth:clear-resets')->everyFifteenMinutes();
            $schedule->command(GetPrescriptions::class)->everyMinute();
            $schedule->command(GetPrescriptionsByLibertyScriptNumber::class)->everyMinute();
            $schedule->command(GetOrderShipments::class)->everyFiveMinutes();
            $schedule->command(CheckForExpiredOrders::class)->everyTenMinutes();
            $schedule->command(HandleOrderSubscriptions::class)->hourly();
            $schedule->command(OrderStatusUpdate::class)->everyFiveMinutes();
            
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
