<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingDay;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingDayItineraryTest extends TestCase
{
    use RefreshDatabase;

    protected User $agent;
    protected Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->agent = User::create([
            'name' => 'Safari Agent',
            'email' => 'agent@uhalisiawatanzania.com',
            'password' => bcrypt('AgentSecret123!'),
            'role' => 'agent',
            'active' => true,
        ]);

        $client = Client::create([
            'full_name' => 'George Harrison',
            'email' => 'george@example.com',
            'country' => 'United Kingdom',
        ]);

        $this->booking = Booking::create([
            'client_id' => $client->id,
            'title' => 'Serengeti & Ngorongoro Explorer',
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-15',
            'adults' => 2,
            'children' => 0,
            'status' => 'confirmed',
            'payment_mode' => 'company_handles',
            'total_price_usd' => 3200.00,
        ]);
    }

    public function test_can_add_itinerary_day_with_auto_calculated_date(): void
    {
        $response = $this->actingAs($this->agent)
                         ->post("/admin/bookings/{$this->booking->id}/days", [
                             'day_number' => 1,
                             'title' => 'Arusha to Lake Manyara',
                             'destination' => 'Lake Manyara',
                             'accommodation' => 'Manyara Serena Safari Lodge',
                             'meals' => 'Lunch, Dinner',
                             'activities' => 'Game drive and bird watching',
                             'description' => 'Drive through the Great Rift Valley.',
                         ]);

        $response->assertSessionHasNoErrors();

        $day = BookingDay::where('booking_id', $this->booking->id)->where('day_number', 1)->first();
        $this->assertNotNull($day);
        $this->assertEquals('Arusha to Lake Manyara', $day->title);
        $this->assertEquals('2026-11-10', $day->date->format('Y-m-d'));
    }

    public function test_can_update_booking_day_details(): void
    {
        $day = BookingDay::create([
            'booking_id' => $this->booking->id,
            'day_number' => 2,
            'date' => '2026-11-11',
            'title' => 'Old Title',
            'destination' => 'Serengeti',
            'accommodation' => 'Tent',
        ]);

        $response = $this->actingAs($this->agent)
                         ->put("/admin/bookings/{$this->booking->id}/days/{$day->id}", [
                             'day_number' => 2,
                             'date' => '2026-11-11',
                             'title' => 'Central Serengeti Big Cat Safari',
                             'destination' => 'Seronera, Serengeti',
                             'accommodation' => 'Kubu Kubu Tented Lodge',
                             'meals' => 'All Meals',
                             'activities' => 'Full day game drive',
                         ]);

        $response->assertSessionHasNoErrors();

        $day->refresh();
        $this->assertEquals('Central Serengeti Big Cat Safari', $day->title);
        $this->assertEquals('Kubu Kubu Tented Lodge', $day->accommodation);
    }

    public function test_can_reorder_days_and_recalculate_calendar_dates(): void
    {
        $day1 = BookingDay::create([
            'booking_id' => $this->booking->id,
            'day_number' => 1,
            'date' => '2026-11-10',
            'title' => 'Tarangire Safari',
        ]);

        $day2 = BookingDay::create([
            'booking_id' => $this->booking->id,
            'day_number' => 2,
            'date' => '2026-11-11',
            'title' => 'Ngorongoro Crater',
        ]);

        // Move Day 2 up to position 1
        $response = $this->actingAs($this->agent)
                         ->post("/admin/bookings/{$this->booking->id}/days/reorder", [
                             'day_id' => $day2->id,
                             'direction' => 'up',
                         ]);

        $response->assertSessionHasNoErrors();

        $day2->refresh();
        $day1->refresh();

        $this->assertEquals(1, $day2->day_number);
        $this->assertEquals('2026-11-10', $day2->date->format('Y-m-d'));

        $this->assertEquals(2, $day1->day_number);
        $this->assertEquals('2026-11-11', $day1->date->format('Y-m-d'));
    }

    public function test_can_delete_booking_day_and_auto_resequence(): void
    {
        $day1 = BookingDay::create(['booking_id' => $this->booking->id, 'day_number' => 1, 'title' => 'Day 1']);
        $day2 = BookingDay::create(['booking_id' => $this->booking->id, 'day_number' => 2, 'title' => 'Day 2']);
        $day3 = BookingDay::create(['booking_id' => $this->booking->id, 'day_number' => 3, 'title' => 'Day 3']);

        $response = $this->actingAs($this->agent)
                         ->delete("/admin/bookings/{$this->booking->id}/days/{$day2->id}");

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('booking_days', ['id' => $day2->id]);
        $this->assertEquals(2, $day3->fresh()->day_number);
    }
}
