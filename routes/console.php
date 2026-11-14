<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Phase 4: Scheduled Tasks ──────────────────────────────────────────────
// These schedules require the Laravel scheduler cron entry on the server:
//   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1

// Daily database backup at 02:00 with 30-day rotation
Schedule::command('app:daily-backup')->dailyAt('02:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/backup.log'));

// Trip reminder emails at 08:00 for safaris starting in 3 days
Schedule::command('app:trip-reminder')->dailyAt('08:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/reminders.log'));

// Weekly operational summary email every Monday at 07:00
Schedule::command('app:weekly-summary')->weeklyOn(1, '07:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/weekly-summary.log'));
