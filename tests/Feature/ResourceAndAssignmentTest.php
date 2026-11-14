<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Guide;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceAndAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private User $agent;
    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->agent = User::create([
            'name' => 'Safari Agent',
            'email' => 'agent@uhalisia.test',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'active' => true,
        ]);

        $client = Client::create([
            'full_name' => 'Hans Schmidt',
            'email' => 'hans@example.de',
            'country' => 'Germany',
        ]);

        $this->booking = Booking::create([
            'reference' => 'UWT-2026-TEST',
            'public_token' => 'test-token-resource-123456',
            'client_id' => $client->id,
            'title' => '7-Day Serengeti Wildlife Safari',
            'status' => 'confirmed',
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-07',
            'adults' => 2,
            'children' => 0,
            'total_price_usd' => 4500.00,
            'discount_usd' => 0.00,
            'payment_mode' => 'company_handles',
        ]);
    }

    public function test_agent_can_crud_safari_vehicles(): void
    {
        $this->actingAs($this->agent);

        // 1. Create
        $response = $this->post(route('admin.vehicles.store'), [
            'name' => 'Cruiser 05 (Tarangire)',
            'plate_number' => 'T 882 DZA',
            'type' => 'Extended 4x4 Land Cruiser',
            'seats' => 7,
            'active' => 1,
            'notes' => 'Inverter 220V installed',
        ]);

        $response->assertRedirect(route('admin.vehicles.index'));
        $this->assertDatabaseHas('vehicles', ['plate_number' => 'T 882 DZA']);

        $vehicle = Vehicle::where('plate_number', 'T 882 DZA')->first();

        // 2. Edit / Update
        $updateResp = $this->put(route('admin.vehicles.update', $vehicle->id), [
            'name' => 'Cruiser 05 (Tarangire Extended)',
            'plate_number' => 'T 882 DZA',
            'type' => 'Extended 4x4 Land Cruiser',
            'seats' => 8,
            'active' => 1,
            'notes' => 'Fridge onboard added',
        ]);

        $updateResp->assertRedirect(route('admin.vehicles.index'));
        $this->assertDatabaseHas('vehicles', ['name' => 'Cruiser 05 (Tarangire Extended)', 'seats' => 8]);

        // 3. Delete
        $delResp = $this->delete(route('admin.vehicles.destroy', $vehicle->id));
        $delResp->assertRedirect(route('admin.vehicles.index'));
        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }

    public function test_agent_can_crud_safari_drivers_and_guides(): void
    {
        $this->actingAs($this->agent);

        // Driver CRUD
        $this->post(route('admin.drivers.store'), [
            'name' => 'Peter Mollel',
            'phone' => '+255 754 000 111',
            'languages' => 'English, Swahili, German',
            'licence_number' => 'TZ-DL-991',
            'active' => 1,
        ])->assertRedirect(route('admin.drivers.index'));

        $driver = Driver::where('licence_number', 'TZ-DL-991')->first();
        $this->assertNotNull($driver);

        // Guide CRUD
        $this->post(route('admin.guides.store'), [
            'name' => 'Emmanuel Mremi',
            'phone' => '+255 784 111 222',
            'languages' => 'English, Swahili, French',
            'permit_number' => 'TNP-GD-882',
            'active' => 1,
        ])->assertRedirect(route('admin.guides.index'));

        $guide = Guide::where('permit_number', 'TNP-GD-882')->first();
        $this->assertNotNull($guide);
    }

    public function test_can_assign_resource_and_overlap_detection_blocks_conflicting_assignment(): void
    {
        $this->actingAs($this->agent);

        $vehicle = Vehicle::create([
            'name' => 'Cruiser 01',
            'plate_number' => 'T 111 AAA',
            'type' => 'Extended 4x4 Land Cruiser',
            'seats' => 7,
            'active' => true,
        ]);

        // Assignment 1 to Booking 1 (Nov 1 - Nov 7)
        $resp1 = $this->post(route('admin.bookings.assignments.store', $this->booking->id), [
            'resource_type' => 'vehicle',
            'resource_id' => $vehicle->id,
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-07',
        ]);
        $resp1->assertSessionHas('success');
        $this->assertDatabaseHas('assignments', [
            'booking_id' => $this->booking->id,
            'resource_type' => 'vehicle',
            'resource_id' => $vehicle->id,
        ]);

        // Create Booking 2 overlapping (Nov 5 - Nov 12)
        $booking2 = Booking::create([
            'reference' => 'UWT-2026-SECOND',
            'public_token' => 'token-second-overlap-789',
            'client_id' => $this->booking->client_id,
            'title' => 'Tarangire Safari',
            'status' => 'confirmed',
            'start_date' => '2026-11-05',
            'end_date' => '2026-11-12',
            'adults' => 2,
            'children' => 0,
            'total_price_usd' => 3000.00,
            'discount_usd' => 0.00,
            'payment_mode' => 'company_handles',
        ]);

        // Attempting to assign same vehicle to Booking 2 on overlapping dates must FAIL
        $resp2 = $this->post(route('admin.bookings.assignments.store', $booking2->id), [
            'resource_type' => 'vehicle',
            'resource_id' => $vehicle->id,
            'start_date' => '2026-11-05',
            'end_date' => '2026-11-12',
        ]);

        $resp2->assertSessionHas('error');
        $this->assertDatabaseMissing('assignments', [
            'booking_id' => $booking2->id,
            'resource_type' => 'vehicle',
            'resource_id' => $vehicle->id,
        ]);

        // Non-overlapping date (Nov 15 - Nov 20) on Booking 2 must SUCCEED
        $resp3 = $this->post(route('admin.bookings.assignments.store', $booking2->id), [
            'resource_type' => 'vehicle',
            'resource_id' => $vehicle->id,
            'start_date' => '2026-11-15',
            'end_date' => '2026-11-20',
        ]);
        $resp3->assertSessionHas('success');
        $this->assertDatabaseHas('assignments', [
            'booking_id' => $booking2->id,
            'resource_type' => 'vehicle',
            'resource_id' => $vehicle->id,
        ]);
    }

    public function test_can_remove_resource_assignment(): void
    {
        $this->actingAs($this->agent);

        $driver = Driver::create([
            'name' => 'Salim Bakari',
            'phone' => '+255 713 000 999',
            'active' => true,
        ]);

        $assignment = Assignment::create([
            'booking_id' => $this->booking->id,
            'resource_type' => 'driver',
            'resource_id' => $driver->id,
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-07',
        ]);

        $resp = $this->delete(route('admin.bookings.assignments.destroy', [
            'booking' => $this->booking->id,
            'assignment' => $assignment->id,
        ]));

        $resp->assertSessionHas('success');
        $this->assertDatabaseMissing('assignments', ['id' => $assignment->id]);
    }
}
