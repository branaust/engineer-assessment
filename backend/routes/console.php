<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Jobs
|--------------------------------------------------------------------------
|
| TODO: Schedule the UpdateStatisticsJob to run every 5 minutes
|
| Example:
| Schedule::job(new UpdateStatisticsJob)->everyFiveMinutes();
|
| Learn more: https://laravel.com/docs/scheduling
|
*/
