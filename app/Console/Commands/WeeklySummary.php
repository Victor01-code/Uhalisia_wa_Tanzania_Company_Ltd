<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WeeklySummary extends Command
{
    protected $signature = 'app:weekly-summary';

    protected $description = 'Email a weekly operational summary to the admin team';

    public function handle(): int
    {
        $this->info('Generating weekly operational summary...');

        $companyName = Setting::get('company_name', 'Uhalisia wa Tanzania Company Ltd');
        $companyEmail = Setting::get('company_email', 'info@uhalisiawatanzania.com');
        $adminEmail = Setting::get('admin_notification_email', $companyEmail);

        if (! $adminEmail) {
            $this->error('No admin notification email configured. Set admin_notification_email in Settings.');
            return self::FAILURE;
        }

        $now = Carbon::now();
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();
        $next7Days = $now->copy()->addDays(7)->toDateString();

        // Upcoming trips: starting in the next 7 days
        $upcomingTrips = Booking::with(['client', 'tour'])
            ->whereBetween('start_date', [$now->toDateString(), $next7Days])
            ->whereNotIn('status', [Booking::STATUS_CANCELLED])
            ->orderBy('start_date')
            ->get();

        // New inquiries / leads this week
        $newLeads = Lead::where('created_at', '>=', $weekStart)
            ->where('created_at', '<=', $weekEnd)
            ->count();

        // New bookings created this week
        $newBookings = Booking::where('created_at', '>=', $weekStart)
            ->where('created_at', '<=', $weekEnd)
            ->count();

        // Outstanding balances (confirmed/paid bookings with remaining balance)
        $outstandingBookings = Booking::with(['client', 'payments'])
            ->where('payment_mode', 'company_handles')
            ->whereIn('status', [
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_PAID_IN_FULL,
                Booking::STATUS_IN_PROGRESS,
            ])
            ->get()
            ->filter(fn ($b) => $b->balanceUsd() > 0.01);

        $totalOutstandingUsd = $outstandingBookings->sum(fn ($b) => $b->balanceUsd());

        // Payments received this week
        $weeklyPayments = Payment::where('paid_on', '>=', $weekStart->toDateString())
            ->where('paid_on', '<=', $weekEnd->toDateString())
            ->where('type', '!=', 'refund')
            ->get();
        $weeklyIncomeUsd = (float) $weeklyPayments->sum('amount_usd');

        // Active trips (in progress right now)
        $activeTrips = Booking::with(['client', 'tour'])
            ->where('start_date', '<=', $now->toDateString())
            ->where('end_date', '>=', $now->toDateString())
            ->whereNotIn('status', [Booking::STATUS_CANCELLED])
            ->count();

        try {
            Mail::send('emails.weekly_summary', [
                'companyName'        => $companyName,
                'weekLabel'          => $weekStart->format('M d') . ' – ' . $weekEnd->format('M d, Y'),
                'upcomingTrips'      => $upcomingTrips,
                'newLeads'           => $newLeads,
                'newBookings'        => $newBookings,
                'outstandingBookings' => $outstandingBookings,
                'totalOutstandingUsd' => $totalOutstandingUsd,
                'weeklyIncomeUsd'    => $weeklyIncomeUsd,
                'activeTrips'        => $activeTrips,
            ], function ($m) use ($adminEmail, $companyName, $companyEmail, $weekStart) {
                $m->to($adminEmail)
                  ->from($companyEmail, $companyName)
                  ->subject("[Weekly Ops Report] {$companyName} – Week of {$weekStart->format('M d, Y')}");
            });

            $this->info("Weekly summary sent to {$adminEmail}");
            Log::info("WeeklySummary: sent to {$adminEmail}");
            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error("Failed to send weekly summary: {$e->getMessage()}");
            Log::error('WeeklySummary: email failed', ['error' => $e->getMessage()]);
            return self::FAILURE;
        }
    }
}
