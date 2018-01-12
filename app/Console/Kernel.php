<?php

namespace App\Console;

use App\Services\UpdateCurrentlyPlaying;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SchedulerDaemon::class,
        UpdateCurrentlyPlaying::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(UpdateCurrentlyPlaying::class)
            ->timezone('America/Chicago')
            ->weekdays()
            ->between('7:00', '17:00')
            ->everyMinute()
            ->withoutOverlapping();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
