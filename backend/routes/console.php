<?php

use App\Jobs\UpdateStatisticsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Update cached search statistics every 5 minutes via the queue worker
Schedule::job(new UpdateStatisticsJob)->everyFiveMinutes();
