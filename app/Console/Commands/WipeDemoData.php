<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Models\Booking;
use App\Models\BookingDay;
use App\Models\Client;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\StatusHistory;
use App\Models\Traveler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WipeDemoData extends Command
{
    protected $signature = 'app:wipe-demo-data {--force : Bypass confirmation prompt}';
    protected $description = 'Wipe all bookings, payments, leads, clients, and travelers while preserving core tours, users, and settings';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Are you sure you want to wipe all bookings, leads, clients, and transactional records? This cannot be undone!')) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        $this->warn('Wiping transactional demo data...');

        DB::transaction(function () {
            Document::truncate();
            Expense::truncate();
            Payment::truncate();
            Assignment::truncate();
            Traveler::truncate();
            BookingDay::truncate();
            StatusHistory::truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Booking::truncate();
            Lead::truncate();
            Client::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });

        $this->info('Transactional demo data wiped successfully. Core tours, users, settings, vehicles, and drivers remain intact.');
        return Command::SUCCESS;
    }
}
