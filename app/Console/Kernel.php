<?php

namespace App\Console;
use  App\Http\Controllers\AttendenceController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        
        // $schedule ->call(function(){
        //     info('called every minute');
        // })->everyMinute();

        $schedule->command('app:mark-attendence')->timezone('Asia/Kolkata')->daily();

        // $schedule->call([AttendenceController::class, 'showQr'])->dailyAt('00:24');
        
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
