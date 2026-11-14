<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $agent;
    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $this->agent = User::create([
            'name' => 'Safari Coordinator',
            'email' => 'coordinator@uhalisia.test',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'active' => true,
        ]);

        $client = Client::create([
            'full_name' => 'Claire Delacour',
            'email' => 'claire@example.fr',
            'country' => 'France',
        ]);

        $this->booking = Booking::create([
            'reference' => 'UWT-2026-DOCS',
            'public_token' => 'client-token-doc-secure-999',
            'client_id' => $client->id,
            'title' => 'Migration Safari Serengeti',
            'status' => 'confirmed',
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-18',
            'adults' => 2,
            'children' => 0,
            'total_price_usd' => 5200.00,
            'discount_usd' => 0.00,
            'payment_mode' => 'company_handles',
        ]);
    }

    public function test_staff_can_upload_secure_document(): void
    {
        $this->actingAs($this->agent);

        $file = UploadedFile::fake()->create('flight_tickets.pdf', 500, 'application/pdf');

        $response = $this->post(route('admin.bookings.documents.store', $this->booking->id), [
            'title' => 'Coastal Aviation Flight Tickets',
            'file' => $file,
            'visible_to_client' => 1,
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'booking_id' => $this->booking->id,
            'title' => 'Coastal Aviation Flight Tickets',
            'visible_to_client' => 1,
        ]);

        $doc = Document::first();
        Storage::disk('local')->assertExists($doc->file_path);
    }

    public function test_staff_can_download_and_toggle_document_visibility(): void
    {
        $this->actingAs($this->agent);

        $path = Storage::disk('local')->putFile('documents/' . $this->booking->id, UploadedFile::fake()->create('permit.pdf', 200, 'application/pdf'));

        $doc = Document::create([
            'booking_id' => $this->booking->id,
            'title' => 'Serengeti Park Entry Permits',
            'file_path' => $path,
            'visible_to_client' => true,
            'uploaded_by' => $this->agent->id,
        ]);

        // Download by staff
        $downResp = $this->get(route('admin.bookings.documents.download', [$this->booking->id, $doc->id]));
        $downResp->assertOk();

        // Toggle visibility to hidden
        $toggleResp = $this->patch(route('admin.bookings.documents.toggle_visibility', [$this->booking->id, $doc->id]));
        $toggleResp->assertSessionHas('success');
        $this->assertFalse($doc->fresh()->visible_to_client);

        // Toggle back to visible
        $this->patch(route('admin.bookings.documents.toggle_visibility', [$this->booking->id, $doc->id]));
        $this->assertTrue($doc->fresh()->visible_to_client);
    }

    public function test_client_portal_respects_document_visibility(): void
    {
        $path1 = Storage::disk('local')->putFile('documents/' . $this->booking->id, UploadedFile::fake()->create('client_doc.pdf', 100, 'application/pdf'));
        $publicDoc = Document::create([
            'booking_id' => $this->booking->id,
            'title' => 'Safari Packing Guide & Checklist',
            'file_path' => $path1,
            'visible_to_client' => true,
            'uploaded_by' => $this->agent->id,
        ]);

        $path2 = Storage::disk('local')->putFile('documents/' . $this->booking->id, UploadedFile::fake()->create('internal_quote.pdf', 100, 'application/pdf'));
        $hiddenDoc = Document::create([
            'booking_id' => $this->booking->id,
            'title' => 'Supplier Cost Breakdown (Confidential)',
            'file_path' => $path2,
            'visible_to_client' => false,
            'uploaded_by' => $this->agent->id,
        ]);

        // Client downloads public document -> 200 OK
        $resp1 = $this->get(route('client.documents.download', [
            'token' => $this->booking->public_token,
            'document' => $publicDoc->id,
        ]));
        $resp1->assertOk();

        // Client tries downloading hidden document -> 404 Forbidden / Not found
        $resp2 = $this->get(route('client.documents.download', [
            'token' => $this->booking->public_token,
            'document' => $hiddenDoc->id,
        ]));
        $resp2->assertStatus(404);
    }

    public function test_staff_can_delete_document(): void
    {
        $this->actingAs($this->agent);

        $path = Storage::disk('local')->putFile('documents/' . $this->booking->id, UploadedFile::fake()->create('delete_me.pdf', 100, 'application/pdf'));
        $doc = Document::create([
            'booking_id' => $this->booking->id,
            'title' => 'Old Draft Vouchers',
            'file_path' => $path,
            'visible_to_client' => true,
            'uploaded_by' => $this->agent->id,
        ]);

        $delResp = $this->delete(route('admin.bookings.documents.destroy', [
            'booking' => $this->booking->id,
            'document' => $doc->id,
        ]));

        $delResp->assertSessionHas('success');
        $this->assertDatabaseMissing('documents', ['id' => $doc->id]);
        Storage::disk('local')->assertMissing($path);
    }
}
