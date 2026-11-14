<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Client;
use App\Models\StatusHistory;
use App\Models\Tour;
use App\Models\TourDay;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected User $agent;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->agent = User::create([
            'name' => 'Safari Agent',
            'email' => 'agent@uhalisiawatanzania.com',
            'password' => bcrypt('Secret123!'),
            'role' => 'agent',
            'active' => true,
        ]);

        $this->client = Client::create([
            'full_name' => 'Sophia Lauren',
            'email' => 'sophia@example.com',
            'country' => 'Italy',
        ]);
    }

    public function test_booking_creation_generates_reference_and_clones_tour_itinerary_days(): void
    {
        $tour = Tour::create([
            'name' => '3-Day Highlights of Tarangire & Manyara',
            'slug' => '3-day-tarangire-manyara',
            'type' => 'safari',
            'days' => 2,
            'base_price_usd' => 1200.00,
            'active' => true,
        ]);

        TourDay::create([
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Tarangire National Park Game Drive',
            'description' => 'Huge elephant herds and ancient baobabs.',
            'destination' => 'Tarangire',
            'accommodation_note' => 'Tarangire Safari Lodge',
            'meals' => 'Lunch, Dinner',
        ]);

        TourDay::create([
            'tour_id' => $tour->id,
            'day_number' => 2,
            'title' => 'Lake Manyara Flamingos & Tree Climbing Lions',
            'description' => 'Alkaline lake with abundant birdlife.',
            'destination' => 'Lake Manyara',
            'accommodation_note' => 'Return to Arusha',
            'meals' => 'Breakfast, Lunch',
        ]);

        $bookingData = [
            'client_id' => $this->client->id,
            'tour_id' => $tour->id,
            'title' => 'Sophia Lauren - 3-Day Safari Expedition',
            'start_date' => date('Y-m-d', strtotime('+40 days')),
            'end_date' => date('Y-m-d', strtotime('+42 days')),
            'adults' => 2,
            'children' => 0,
            'status' => 'inquiry',
            'payment_mode' => 'company_handles',
            'total_price_usd' => 2400.00,
            'discount_usd' => 100.00,
            'notes' => 'Honeymoon safari setup.',
        ];

        $response = $this->actingAs($this->agent)
                         ->post('/admin/bookings', $bookingData);

        $booking = Booking::where('client_id', $this->client->id)->first();
        $this->assertNotNull($booking);

        // 1. Reference format check: UWT-YYYY-0001
        $currentYear = date('Y');
        $this->assertMatchesRegularExpression("/^UWT-{$currentYear}-\d{4}$/", $booking->reference);

        // 2. Public secure token generated
        $this->assertNotEmpty($booking->public_token);
        $this->assertEquals(64, strlen($booking->public_token));

        // 3. Tour days copied to booking_days
        $this->assertCount(2, $booking->bookingDays);
        $this->assertEquals('Tarangire National Park Game Drive', $booking->bookingDays[0]->title);
        $this->assertEquals('Lake Manyara Flamingos & Tree Climbing Lions', $booking->bookingDays[1]->title);

        // 4. Status history logged
        $this->assertDatabaseHas('status_history', [
            'entity' => 'booking',
            'entity_id' => $booking->id,
            'to_status' => 'inquiry',
            'changed_by' => $this->agent->id,
        ]);
    }

    public function test_booking_status_update_is_audited(): void
    {
        $booking = Booking::create([
            'client_id' => $this->client->id,
            'title' => 'Test Booking',
            'start_date' => date('Y-m-d', strtotime('+10 days')),
            'end_date' => date('Y-m-d', strtotime('+15 days')),
            'adults' => 2,
            'children' => 0,
            'status' => 'quote_sent',
            'payment_mode' => 'company_handles',
            'total_price_usd' => 3000.00,
        ]);

        $response = $this->actingAs($this->agent)
                         ->patch("/admin/bookings/{$booking->id}/status", [
                             'status' => 'confirmed',
                             'note' => 'Client accepted quote and paid 30% deposit.',
                         ]);

        $this->assertEquals('confirmed', $booking->fresh()->status);

        $this->assertDatabaseHas('status_history', [
            'entity' => 'booking',
            'entity_id' => $booking->id,
            'from_status' => 'quote_sent',
            'to_status' => 'confirmed',
            'note' => 'Client accepted quote and paid 30% deposit.',
        ]);
    }

    public function test_client_portal_access_with_valid_token(): void
    {
        $booking = Booking::create([
            'client_id' => $this->client->id,
            'title' => 'Client Viewable Safari',
            'start_date' => date('Y-m-d', strtotime('+20 days')),
            'end_date' => date('Y-m-d', strtotime('+25 days')),
            'adults' => 2,
            'children' => 0,
            'status' => 'confirmed',
            'payment_mode' => 'company_handles',
            'total_price_usd' => 3500.00,
        ]);

        $response = $this->get("/client/b/{$booking->public_token}");

        $response->assertStatus(200);
        $response->assertSee($this->client->full_name);
        $response->assertSee($booking->reference);
    }

    public function test_client_portal_login_page_renders_and_dispatches_magic_link(): void
    {
        $booking = Booking::create([
            'client_id' => $this->client->id,
            'title' => 'Migration Encounter',
            'start_date' => date('Y-m-d', strtotime('+30 days')),
            'end_date' => date('Y-m-d', strtotime('+37 days')),
            'adults' => 2,
            'children' => 0,
            'status' => 'confirmed',
            'payment_mode' => 'company_handles',
            'total_price_usd' => 4800.00,
        ]);

        // Login page renders without route error
        $loginResp = $this->get(route('client.login'));
        $loginResp->assertOk();
        $loginResp->assertSee('Your Booking Email Address');

        // Request magic link
        \Illuminate\Support\Facades\Mail::fake();

        $postResp = $this->post(route('client.request_link'), [
            'email' => $this->client->email,
        ]);

        $postResp->assertSessionHas('status');
        $this->assertNotEmpty($booking->fresh()->public_token);
    }

    public function test_client_portal_renders_assigned_crew_and_vehicle(): void
    {
        $vehicle = \App\Models\Vehicle::create([
            'name' => 'Extended Cruiser 03',
            'plate_number' => 'T 555 BCD',
            'type' => 'Extended 4x4 Land Cruiser',
            'seats' => 7,
            'active' => true,
        ]);

        $driver = \App\Models\Driver::create([
            'name' => 'Baraka Tarimo',
            'phone' => '+255 754 888 999',
            'active' => true,
        ]);

        $booking = Booking::create([
            'client_id' => $this->client->id,
            'title' => 'Serengeti Safari with Crew',
            'start_date' => date('Y-m-d', strtotime('+10 days')),
            'end_date' => date('Y-m-d', strtotime('+15 days')),
            'adults' => 2,
            'children' => 0,
            'status' => 'confirmed',
            'payment_mode' => 'company_handles',
            'total_price_usd' => 3800.00,
        ]);

        $booking->assignments()->create([
            'resource_type' => 'vehicle',
            'resource_id' => $vehicle->id,
            'start_date' => $booking->start_date,
            'end_date' => $booking->end_date,
        ]);

        $booking->assignments()->create([
            'resource_type' => 'driver',
            'resource_id' => $driver->id,
            'start_date' => $booking->start_date,
            'end_date' => $booking->end_date,
        ]);

        $response = $this->get(route('client.show', $booking->public_token));
        $response->assertOk();
        $response->assertSee('Your Safari Crew', false);
        $response->assertSee('Extended Cruiser 03');
        $response->assertSee('Baraka Tarimo');
        $response->assertSee('+255 754 888 999');
    }

    public function test_admin_can_view_booking_detail_page(): void
    {
        $booking = Booking::create([
            'client_id' => $this->client->id,
            'title' => 'Serengeti Migration Adventure',
            'start_date' => date('Y-m-d', strtotime('+5 days')),
            'end_date' => date('Y-m-d', strtotime('+10 days')),
            'adults' => 2,
            'children' => 0,
            'status' => 'confirmed',
            'payment_mode' => 'company_handles',
            'total_price_usd' => 4500.00,
        ]);

        $response = $this->actingAs($this->agent)->get("/admin/bookings/{$booking->id}");
        $response->assertOk();
        $response->assertSee('Serengeti Migration Adventure');
        $response->assertSee($this->client->full_name);
    }
}
