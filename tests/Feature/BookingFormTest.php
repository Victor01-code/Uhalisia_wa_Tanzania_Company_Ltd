<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Lead;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_page_renders_successfully(): void
    {
        Tour::create([
            'name' => '7-Day Classic Serengeti & Ngorongoro',
            'slug' => '7-day-serengeti-ngorongoro',
            'type' => 'safari',
            'days' => 7,
            'base_price_usd' => 2800.00,
            'active' => true,
        ]);

        $response = $this->get('/book?tour=7-day-serengeti-ngorongoro&utm_source=google&utm_medium=cpc');

        $response->assertStatus(200);
        $response->assertSee('7-Day Classic Serengeti & Ngorongoro');
    }

    public function test_valid_booking_submission_creates_client_and_lead(): void
    {
        Mail::fake();

        $tour = Tour::create([
            'name' => '5-Day Big Five Safari',
            'slug' => '5-day-big-five-safari',
            'type' => 'safari',
            'days' => 5,
            'base_price_usd' => 2100.00,
            'active' => true,
        ]);

        $payload = [
            'name' => 'Jane Traveler',
            'email' => 'jane.traveler@example.com',
            'phone' => '+1 555 123 4567',
            'country' => 'United States',
            'tour_slug' => $tour->slug,
            'safari_interest' => 'Luxury lodge safari with migration viewing',
            'start_date' => date('Y-m-d', strtotime('+30 days')),
            'adults' => 2,
            'children' => 1,
            'message' => 'Interested in hot air balloon safari in Serengeti.',
            'utm_source' => 'facebook',
            'utm_medium' => 'social_ad',
            'website_url' => '', // honeypot left empty
        ];

        $response = $this->post('/book', $payload);

        $response->assertRedirect('/book/thank-you');

        // Check Client was created
        $this->assertDatabaseHas('clients', [
            'email' => 'jane.traveler@example.com',
            'full_name' => 'Jane Traveler',
            'country' => 'United States',
        ]);

        // Check Lead was created with UTM tags and book_form source
        $this->assertDatabaseHas('leads', [
            'email' => 'jane.traveler@example.com',
            'name' => 'Jane Traveler',
            'source' => 'book_form',
            'utm_source' => 'facebook',
            'utm_medium' => 'social_ad',
            'status' => 'new',
        ]);
    }

    public function test_honeypot_spam_trap_silently_redirects_without_creating_records(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Spam Bot',
            'email' => 'spambot@example.org',
            'country' => 'Unknown',
            'adults' => 2,
            'website_url' => 'http://spam-link-generator.ru', // honeypot tripped!
        ];

        $response = $this->post('/book', $payload);

        $response->assertRedirect('/book/thank-you');

        $this->assertDatabaseMissing('leads', [
            'email' => 'spambot@example.org',
        ]);

        $this->assertDatabaseMissing('clients', [
            'email' => 'spambot@example.org',
        ]);
    }

    public function test_booking_submission_validates_required_fields(): void
    {
        $response = $this->post('/book', [
            'name' => '',
            'email' => 'not-an-email',
            'country' => '',
            'adults' => 0,
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'country', 'adults']);
    }

    public function test_booking_page_renders_country_dropdown_with_dial_codes(): void
    {
        $response = $this->get('/book');

        $response->assertStatus(200);
        $response->assertSee('Country of Residence');
        $response->assertSee('name="country"', false);
        $response->assertSee('phone_country_code', false);
        $response->assertSee('Tanzania (+255)');
        $response->assertSee('United States (+1)');
        $response->assertSee('United Kingdom (+44)');
    }

    public function test_booking_submission_with_country_and_separate_phone_fields_merges_dial_code(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Oliver Smith',
            'email' => 'oliver.smith@example.co.uk',
            'country' => 'United Kingdom',
            'phone_country_code' => '+44',
            'phone_number' => '7700 900123',
            'adults' => 2,
            'website_url' => '',
        ];

        $response = $this->post('/book', $payload);

        $response->assertRedirect('/book/thank-you');

        $this->assertDatabaseHas('clients', [
            'email' => 'oliver.smith@example.co.uk',
            'full_name' => 'Oliver Smith',
            'country' => 'United Kingdom',
            'phone' => '+44 7700 900123',
        ]);

        $this->assertDatabaseHas('leads', [
            'email' => 'oliver.smith@example.co.uk',
            'name' => 'Oliver Smith',
            'country' => 'United Kingdom',
        ]);
    }
}

