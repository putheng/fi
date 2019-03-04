<?php

namespace App\Console;

use App\Http\Common\ORAConsts;
use App\Http\Controllers\CommCareSyncController;
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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //if (App::environment('production')) {
        //Log::info("COMM CARE SYNC SCHEDULED - PRODUCTION");

        $logFile = env('SMS_JOB_LOG_FILE', '/home4/yes4mqdn/logs/ora_logs/sms_job_log.log');
        $schedule->command("sms_job")
            //->everyMinute()
            ->everyFifteenMinutes()
            //->everyThirtyMinutes()
            ->between(env('SMS_TIME_START', '09:05'), env('SMS_TIME_END', '20:55'))
            ->timezone(ORAConsts::TIMEZONE)
            ->appendOutputTo($logFile)
        ;

        $logFile = env('REMINDER_JOB_LOG_FILE', '/home4/yes4mqdn/logs/ora_logs/reminder_job_log.log');
        $schedule->command("reminder_job")
            //->everyMinute()
            ->everyFiveMinutes()
            //->everyThirtyMinutes()
            ->between(env('REMINDER_TIME_START', '09:05'), env('REMINDER_TIME_END', '20:55'))
            ->timezone(ORAConsts::TIMEZONE)
            ->appendOutputTo($logFile)
        ;

        //} else {
        //    Log::info("SMS JOB NOT SCHEDULED - DEVELOPMENT");
        //}

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
