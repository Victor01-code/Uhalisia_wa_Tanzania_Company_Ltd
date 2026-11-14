<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingDay;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\StatusHistory;
use App\Models\Tour;
use App\Models\Traveler;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $agent = User::where('role', 'agent')->first() ?? User::first();
        $accountant = User::where('role', 'accountant')->first() ?? $agent;

        // 1. Clients
        $client1 = Client::updateOrCreate(
            ['email' => 'sarah.jenkins@example.com'],
            [
                'full_name' => 'Sarah Jenkins',
                'phone' => '+44 7700 900123',
                'country' => 'United Kingdom',
                'notes' => 'Honeymoon couple, interested in big cats and luxury tented camps.',
            ]
        );

        $client2 = Client::updateOrCreate(
            ['email' => 'johann.schmidt@example.de'],
            [
                'full_name' => 'Johann Schmidt',
                'phone' => '+49 151 23456789',
                'country' => 'Germany',
                'notes' => 'Photography enthusiast with 600mm lens, requested window seats.',
            ]
        );

        $client3 = Client::updateOrCreate(
            ['email' => 'michelle.dupont@example.fr'],
            [
                'full_name' => 'Michelle Dupont',
                'phone' => '+33 6 12 34 56 78',
                'country' => 'France',
                'notes' => 'Vegetarian dietary requirement, requested French-speaking guide if available.',
            ]
        );

        // 2. Leads
        $lead1 = Lead::updateOrCreate(
            ['email' => 'sarah.jenkins@example.com'],
            [
                'client_id' => $client1->id,
                'name' => 'Sarah Jenkins',
                'country' => 'United Kingdom',
                'safari_interest' => '10-days-safaris',
                'message' => 'Looking for a 10-day northern circuit safari for two adults next month.',
                'source' => 'website_quote_form',
                'utm_source' => 'google',
                'utm_medium' => 'cpc',
                'status' => 'won',
                'assigned_user_id' => $agent->id,
            ]
        );

        $lead2 = Lead::updateOrCreate(
            ['email' => 'alex.rossi@example.it'],
            [
                'client_id' => null,
                'name' => 'Alessandro Rossi',
                'country' => 'Italy',
                'safari_interest' => '6-days-umbwe-route',
                'message' => 'Interested in climbing Kilimanjaro via Umbwe route with a private guide.',
                'source' => 'book_form',
                'utm_source' => 'tripadvisor',
                'utm_medium' => 'referral',
                'status' => 'new',
                'assigned_user_id' => $agent->id,
            ]
        );

        $lead3 = Lead::updateOrCreate(
            ['email' => 'emily.clark@example.com'],
            [
                'client_id' => null,
                'name' => 'Emily Clark',
                'country' => 'United States',
                'safari_interest' => 'bush-to-beach',
                'message' => 'Family vacation with 2 kids, wildlife safari plus 4 days in Zanzibar.',
                'source' => 'website_quote_form',
                'utm_source' => 'facebook',
                'utm_medium' => 'social',
                'status' => 'quoted',
                'assigned_user_id' => $agent->id,
            ]
        );

        // 3. Bookings
        $tour10Days = Tour::where('slug', '10-days-safaris')->first();
        $tour9Days = Tour::where('slug', '9-days-safaris')->first();

        // Confirmed booking for Client 1
        $startDate1 = Carbon::now()->addDays(15);
        $endDate1 = (clone $startDate1)->addDays(9);

        $booking1 = Booking::updateOrCreate(
            ['reference' => 'UWT-' . date('Y') . '-0001'],
            [
                'client_id' => $client1->id,
                'lead_id' => $lead1->id,
                'tour_id' => $tour10Days?->id,
                'title' => '10 Days Northern Circuit Safari - Jenkins Couple',
                'start_date' => $startDate1,
                'end_date' => $endDate1,
                'adults' => 2,
                'children' => 0,
                'status' => Booking::STATUS_CONFIRMED,
                'payment_mode' => Booking::PAYMENT_MODE_COMPANY,
                'total_price_usd' => 6900.00,
                'discount_usd' => 200.00,
                'notes' => 'Complimentary bottle of sparkling wine in Serengeti for honeymoon celebration.',
                'created_by' => $agent->id,
            ]
        );

        // Copy tour days into booking days if empty
        if ($booking1->bookingDays()->count() === 0 && $tour10Days) {
            foreach ($tour10Days->tourDays as $td) {
                $dayDate = (clone $startDate1)->addDays($td->day_number - 1);
                BookingDay::create([
                    'booking_id' => $booking1->id,
                    'day_number' => $td->day_number,
                    'date' => $dayDate,
                    'title' => $td->title,
                    'description' => $td->description,
                    'destination' => $td->destination,
                    'accommodation' => $td->accommodation_note,
                    'meals' => $td->meals,
                    'activities' => $td->activities,
                ]);
            }
        }

        // Travelers for Booking 1
        Traveler::updateOrCreate(
            ['booking_id' => $booking1->id, 'full_name' => 'Sarah Jenkins'],
            [
                'nationality' => 'British',
                'passport_number' => 'GB987654321',
                'date_of_birth' => '1992-06-14',
                'dietary_notes' => 'None',
                'emergency_contact' => 'Mother: +44 7700 900999',
            ]
        );

        Traveler::updateOrCreate(
            ['booking_id' => $booking1->id, 'full_name' => 'Thomas Jenkins'],
            [
                'nationality' => 'British',
                'passport_number' => 'GB123456789',
                'date_of_birth' => '1990-11-23',
                'dietary_notes' => 'Gluten-free preference',
                'emergency_contact' => 'Father: +44 7700 900888',
            ]
        );

        // Payment for Booking 1: 30% Deposit paid
        Payment::updateOrCreate(
            ['booking_id' => $booking1->id, 'reference' => 'BNK-2026-0491'],
            [
                'paid_on' => Carbon::now()->subDays(5),
                'amount' => 2010.00,
                'currency' => 'USD',
                'exchange_rate_used' => 2650.00,
                'amount_usd' => 2010.00,
                'amount_tzs' => 2010.00 * 2650.00,
                'method' => 'bank',
                'received_by' => $accountant->id,
                'notes' => '30% deposit received via wire transfer.',
                'type' => 'deposit',
            ]
        );

        StatusHistory::log('booking', $booking1->id, Booking::STATUS_QUOTE_SENT, Booking::STATUS_CONFIRMED, $agent->id, 'Deposit received, booking confirmed.');

        // Booking 2: In-progress booking for Client 2
        $startDate2 = Carbon::now()->subDays(2);
        $endDate2 = (clone $startDate2)->addDays(8);

        $booking2 = Booking::updateOrCreate(
            ['reference' => 'UWT-' . date('Y') . '-0002'],
            [
                'client_id' => $client2->id,
                'tour_id' => $tour9Days?->id,
                'title' => '9 Days Migration Safari - Schmidt Party',
                'start_date' => $startDate2,
                'end_date' => $endDate2,
                'adults' => 2,
                'children' => 0,
                'status' => Booking::STATUS_IN_PROGRESS,
                'payment_mode' => Booking::PAYMENT_MODE_COMPANY,
                'total_price_usd' => 6300.00,
                'discount_usd' => 0.00,
                'notes' => 'Currently in Serengeti, requested extra packed lunch for full-day game drive.',
                'created_by' => $agent->id,
            ]
        );

        if ($booking2->bookingDays()->count() === 0 && $tour9Days) {
            foreach ($tour9Days->tourDays as $td) {
                $dayDate = (clone $startDate2)->addDays($td->day_number - 1);
                BookingDay::create([
                    'booking_id' => $booking2->id,
                    'day_number' => $td->day_number,
                    'date' => $dayDate,
                    'title' => $td->title,
                    'description' => $td->description,
                    'destination' => $td->destination,
                    'accommodation' => $td->accommodation_note,
                    'meals' => $td->meals,
                    'activities' => $td->activities,
                ]);
            }
        }

        Payment::updateOrCreate(
            ['booking_id' => $booking2->id, 'reference' => 'BNK-2026-0312'],
            [
                'paid_on' => Carbon::now()->subDays(20),
                'amount' => 6300.00,
                'currency' => 'USD',
                'exchange_rate_used' => 2640.00,
                'amount_usd' => 6300.00,
                'amount_tzs' => 6300.00 * 2640.00,
                'method' => 'bank',
                'received_by' => $accountant->id,
                'notes' => 'Paid in full prior to arrival.',
                'type' => 'balance',
            ]
        );
    }
}
