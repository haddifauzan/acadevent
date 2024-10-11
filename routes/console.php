<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\UpdateStatusAcara;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Artisan::command('acara:update-status', function () {
    $this->call(UpdateStatusAcara::class);
})->purpose('Update status acara berdasarkan waktu acara');

