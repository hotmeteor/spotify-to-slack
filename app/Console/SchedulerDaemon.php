<?php

namespace App\Console;

use Illuminate\Console\Command;

class SchedulerDaemon extends Command
{
    protected $signature = 'schedule:daemon {--sleep=60}';

    protected $description = 'Call the scheduler every minute.';

    public function handle()
    {
        while (true) {
            $this->line('<info>['.now()->format('Y-m-d H:i:s').']</info> Calling scheduler');

            $this->call('schedule:run');

            sleep($this->option('sleep'));
        }
    }
}
