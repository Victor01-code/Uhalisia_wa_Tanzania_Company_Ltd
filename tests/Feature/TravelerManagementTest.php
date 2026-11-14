<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Traveler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TravelerManagementTest extends TestCase
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
            'password' => bcrypt('Secret123!'),
            'role' => 'agent',
            'active' => true,
        ]);

        $client = Client::create([
            'full_name' => 'Dr. Robert Langdon',
            'email' => 'robert@harvard.edu',
            'country' => 'United States',
        ]);

        $this->booking = Booking::create([
            'client_id' => $client->id,
            'title' => 'Tanzania Rift Valley Expedition',
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-08',
            'adults' => 2,
            'children' => 0,
            'status' => 'confirmed',
            'payment_mode' => 'company_handles',
            'total_price_usd' => 4500.00,
        ]);
    }

    public function test_can_register_traveler_with_encrypted_passport(): void
    {
        $rawPassport = 'US-9876543210';

        $response = $this->actingAs($this->agent)
                         ->post("/admin/bookings/{$this->booking->id}/travelers", [
                             'full_name' => 'Robert Langdon',
                             'nationality' => 'American',
                             'date_of_birth' => '1964-06-22',
                             'passport_number' => $rawPassport,
                             'dietary_notes' => 'No shellfish, black coffee only',
                             'emergency_contact' => 'Harvard Security (+1 617 495 1000)',
                         ]);

        $response->assertSessionHasNoErrors();

        $traveler = Traveler::where('booking_id', $this->booking->id)->first();
        $this->assertNotNull($traveler);

        // 1. Eloquent transparently decrypts the passport number
        $this->assertEquals($rawPassport, $traveler->passport_number);

        // 2. Database raw query verifies that the stored value is ciphertext, NOT plaintext!
        $rawRow = DB::table('travelers')->where('id', $traveler->id)->first();
        $this->assertNotEquals($rawPassport, $rawRow->passport_number);
        $this->assertStringStartsWith('eyJ', $rawRow->passport_number); // Laravel encrypted payload (base64 JSON)
    }

    public function test_can_update_traveler_dietary_and_details(): void
    {
        $traveler = Traveler::create([
            'booking_id' => $this->booking->id,
            'full_name' => 'Sophie Neveu',
            'nationality' => 'French',
            'dietary_notes' => 'Vegetarian',
        ]);

        $response = $this->actingAs($this->agent)
                         ->put("/admin/bookings/{$this->booking->id}/travelers/{$traveler->id}", [
                             'full_name' => 'Sophie Neveu',
                             'nationality' => 'French',
                             'dietary_notes' => 'Strict Vegan & Gluten Free',
                             'emergency_contact' => 'DCPJ Paris (+33 1 40 00 00 00)',
                         ]);

        $response->assertSessionHasNoErrors();

        $traveler->refresh();
        $this->assertEquals('Strict Vegan & Gluten Free', $traveler->dietary_notes);
        $this->assertEquals('DCPJ Paris (+33 1 40 00 00 00)', $traveler->emergency_contact);
    }

    public function test_can_delete_traveler_from_booking(): void
    {
        $traveler = Traveler::create([
            'booking_id' => $this->booking->id,
            'full_name' => 'Remove Me',
        ]);

        $response = $this->actingAs($this->agent)
                         ->delete("/admin/bookings/{$this->booking->id}/travelers/{$traveler->id}");

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('travelers', ['id' => $traveler->id]);
    }
}
