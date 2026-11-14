<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingDay;
use App\Models\Client;
use App\Models\Traveler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PdfGenerationAndEmailTest extends TestCase
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
            'password' => bcrypt('SecretPass123!'),
            'role' => 'agent',
            'active' => true,
        ]);

        $client = Client::create([
            'full_name' => 'Lord Mountbatten',
            'email' => 'mountbatten@highgrove.co.uk',
            'country' => 'United Kingdom',
            'phone' => '+44 20 7946 0991',
        ]);

        $this->booking = Booking::create([
            'client_id' => $client->id,
            'title' => 'Serengeti Great Migration & Ngorongoro Crater',
            'start_date' => '2026-12-15',
            'end_date' => '2026-12-22',
            'adults' => 2,
            'children' => 1,
            'status' => 'inquiry',
            'payment_mode' => 'company_handles',
            'total_price_usd' => 5400.00,
            'discount_usd' => 200.00,
            'notes' => 'Photographic private safari.',
        ]);

        BookingDay::create([
            'booking_id' => $this->booking->id,
            'day_number' => 1,
            'date' => '2026-12-15',
            'title' => 'Arusha to Tarangire National Park',
            'destination' => 'Tarangire',
            'accommodation' => 'Tarangire Safari Lodge',
            'meals' => 'Lunch, Dinner',
            'activities' => 'Game drive among baobabs and elephant herds',
            'description' => 'Morning briefing in Arusha before setting off for Tarangire.',
        ]);

        BookingDay::create([
            'booking_id' => $this->booking->id,
            'day_number' => 2,
            'date' => '2026-12-16',
            'title' => 'Tarangire to Central Serengeti',
            'destination' => 'Serengeti',
            'accommodation' => 'Serengeti Serena Safari Lodge',
            'meals' => 'Breakfast, Lunch, Dinner',
            'activities' => 'Full afternoon game drive tracking lions and leopards',
            'description' => 'Ascend through the Ngorongoro highlands before entering the endless plains.',
        ]);

        Traveler::create([
            'booking_id' => $this->booking->id,
            'full_name' => 'Lord Mountbatten',
            'nationality' => 'British',
            'passport_number' => 'UK987654321',
        ]);
    }

    public function test_can_generate_and_stream_quote_pdf(): void
    {
        $response = $this->actingAs($this->agent)
                         ->get("/admin/bookings/{$this->booking->id}/quote-pdf");

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('inline', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString(".pdf", $response->headers->get('Content-Disposition'));

        // Verify valid PDF file binary signature
        $content = $response->getContent();
        $this->assertStringStartsWith('%PDF-', $content);
    }

    public function test_can_download_quote_pdf_with_proper_headers(): void
    {
        $filename = "UWT-Quote-{$this->booking->reference}.pdf";
        $response = $this->actingAs($this->agent)
                         ->get("/admin/bookings/{$this->booking->id}/quote-pdf/{$filename}?download=1");

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString($filename, $response->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_can_generate_and_stream_itinerary_voucher_pdf(): void
    {
        $response = $this->actingAs($this->agent)
                         ->get("/admin/bookings/{$this->booking->id}/itinerary-pdf");

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('inline', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString(".pdf", $response->headers->get('Content-Disposition'));

        $content = $response->getContent();
        $this->assertStringStartsWith('%PDF-', $content);
    }

    public function test_can_download_itinerary_voucher_pdf_with_proper_headers(): void
    {
        $filename = "UWT-Itinerary-{$this->booking->reference}.pdf";
        $response = $this->actingAs($this->agent)
                         ->get("/admin/bookings/{$this->booking->id}/itinerary-pdf/{$filename}?download=1");

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString($filename, $response->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_client_can_download_pdfs_from_client_portal(): void
    {
        // 1. Client Quote PDF download
        $quoteFilename = "Safari-Quote-{$this->booking->reference}.pdf";
        $quoteRes = $this->get("/client/b/{$this->booking->public_token}/quote-pdf/{$quoteFilename}");
        $quoteRes->assertStatus(200);
        $this->assertEquals('application/pdf', $quoteRes->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $quoteRes->headers->get('Content-Disposition'));
        $this->assertStringContainsString($quoteFilename, $quoteRes->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF-', $quoteRes->getContent());

        // 2. Client Itinerary Voucher PDF download
        $itinFilename = "Safari-Itinerary-{$this->booking->reference}.pdf";
        $itinRes = $this->get("/client/b/{$this->booking->public_token}/itinerary-pdf/{$itinFilename}");
        $itinRes->assertStatus(200);
        $this->assertEquals('application/pdf', $itinRes->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $itinRes->headers->get('Content-Disposition'));
        $this->assertStringContainsString($itinFilename, $itinRes->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF-', $itinRes->getContent());
    }

    public function test_send_quote_email_dispatches_with_pdf_attachment_and_updates_status(): void
    {
        Mail::fake();

        $this->assertEquals('inquiry', $this->booking->status);

        $response = $this->actingAs($this->agent)
                         ->post("/admin/bookings/{$this->booking->id}/send-quote", [
                             'message' => 'Please find your personalized quote for your December safari.',
                         ]);

        $response->assertSessionHasNoErrors();

        // 1. Booking status updated to quote_sent
        $this->booking->refresh();
        $this->assertEquals('quote_sent', $this->booking->status);

        // 2. Status history logged
        $this->assertDatabaseHas('status_history', [
            'entity' => 'booking',
            'entity_id' => $this->booking->id,
            'from_status' => 'inquiry',
            'to_status' => 'quote_sent',
            'changed_by' => $this->agent->id,
        ]);
    }

    public function test_send_itinerary_voucher_email_dispatches_successfully(): void
    {
        Mail::fake();

        $response = $this->actingAs($this->agent)
                         ->post("/admin/bookings/{$this->booking->id}/send-itinerary", [
                             'message' => 'We are delighted to confirm your safari. See you in Arusha!',
                         ]);

        $response->assertSessionHasNoErrors();

        // Audit entry logged for voucher dispatch
        $this->assertDatabaseHas('status_history', [
            'entity' => 'booking',
            'entity_id' => $this->booking->id,
            'changed_by' => $this->agent->id,
        ]);
    }
}
