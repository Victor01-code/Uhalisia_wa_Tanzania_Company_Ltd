<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TripReminder extends Command
{
    protected $signature = 'app:trip-reminder';

    protected $description = 'Send email reminders for trips starting in 3 days';

    public function handle(): int
    {
        $this->info('Checking for upcoming trips in 3 days...');

        $targetDate = now()->addDays(3)->toDateString();

        $bookings = Booking::with(['client', 'tour', 'travelers', 'assignments'])
            ->where('start_date', $targetDate)
            ->whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_INQUIRY])
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('No trips starting in 3 days. Nothing to send.');
            return self::SUCCESS;
        }

        $companyName = Setting::get('company_name', 'Uhalisia wa Tanzania Company Ltd');
        $companyEmail = Setting::get('company_email', 'info@uhalisiawatanzania.com');
        $companyPhone = Setting::get('company_phone', '+255 784 590 722');
        $sent = 0;
        $errors = 0;

        foreach ($bookings as $booking) {
            $client = $booking->client;

            if (! $client || ! $client->email) {
                $this->warn("Skipping booking {$booking->reference}: no client email.");
                continue;
            }

            $portalUrl = url('/client/b/' . $booking->public_token);

            try {
                Mail::send('emails.trip_reminder', [
                    'booking' => $booking,
                    'client' => $client,
                    'companyName' => $companyName,
                    'companyPhone' => $companyPhone,
                    'portalUrl' => $portalUrl,
                    'daysUntil' => 3,
                ], function ($m) use ($client, $booking, $companyName, $companyEmail) {
                    $m->to($client->email, $client->full_name)
                      ->from($companyEmail, $companyName)
                      ->subject("Your Safari Starts in 3 Days! [{$booking->reference}] - {$companyName}");
                });

                $sent++;
                $this->info("  ✓ Reminder sent to {$client->email} for booking {$booking->reference}");
            } catch (\Throwable $e) {
                $errors++;
                $this->error("  ✗ Failed for {$booking->reference}: {$e->getMessage()}");
                Log::error('TripReminder: email failed', [
                    'booking' => $booking->reference,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Trip reminders complete. Sent: {$sent}, Errors: {$errors}");
        Log::info("TripReminder: {$sent} sent, {$errors} errors for date {$targetDate}");

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
