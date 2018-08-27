<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands;

class Kernel extends \Illuminate\Foundation\Console\Kernel {

    /**
     * @param Schedule $schedule
     */
    protected function schedule(Schedule $schedule) {
        $schedule->command(Commands\NewsLoader::class)->everyTenMinutes();
    }

    protected function commands() {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
