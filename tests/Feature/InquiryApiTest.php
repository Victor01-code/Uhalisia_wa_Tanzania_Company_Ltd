<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InquiryApiTest extends TestCase
{
    use RefreshDatabase;

    protected string $validToken = 'uwt_secret_quote_token_2026';

    public function test_api_inquiries_rejects_missing_or_invalid_bearer_token(): void
    {
        // 1. Missing token
        $response = $this->postJson('/api/inquiries', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
        $response->assertStatus(401)
                 ->assertJson([
                     'ok' => false,
                     'error' => 'Unauthorized: Invalid or missing inquiry token.',
                 ]);

        // 2. Invalid token
        $response = $this->withHeader('Authorization', 'Bearer wrong_token_value')
                         ->postJson('/api/inquiries', [
                             'name' => 'John Doe',
                             'email' => 'john@example.com',
                         ]);
        $response->assertStatus(401)
                 ->assertJson([
                     'ok' => false,
                     'error' => 'Unauthorized: Invalid or missing inquiry token.',
                 ]);
    }

    public function test_api_inquiries_accepts_valid_json_payload_and_creates_records(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Michael Smith',
            'email' => 'michael.smith@example.co.uk',
            'phone' => '+44 7911 123456',
            'country' => 'United Kingdom',
            'safari_interest' => '6-Day Kilimanjaro Machame Route',
            'message' => 'Group of 4 looking to climb in October.',
            'utm_source' => 'google',
            'utm_medium' => 'organic',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->validToken)
                         ->postJson('/api/inquiries', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'ok' => true,
                 ])
                 ->assertJsonStructure(['ok', 'lead_id']);

        $this->assertDatabaseHas('clients', [
            'email' => 'michael.smith@example.co.uk',
            'full_name' => 'Michael Smith',
            'country' => 'United Kingdom',
        ]);

        $this->assertDatabaseHas('leads', [
            'email' => 'michael.smith@example.co.uk',
            'source' => 'website_quote_form',
            'status' => 'new',
            'utm_source' => 'google',
        ]);
    }

    public function test_api_inquiries_tolerates_nested_elementor_form_fields(): void
    {
        Mail::fake();

        // Elementor Pro webhook payload format
        $payload = [
            'fields' => [
                'name' => ['value' => 'Elena Rostova'],
                'email' => ['value' => 'elena.rostova@example.com'],
                'phone' => ['value' => '+49 30 123456'],
                'country' => ['value' => 'Germany'],
                'tour' => ['value' => 'Tarangire & Ngorongoro 3-Day'],
                'message' => ['value' => 'Vegetarian dietary requirements.'],
            ],
            'meta' => [
                'page_url' => 'https://www.uhalisiawatanzania.com/tours/tarangire',
            ],
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->validToken)
                         ->postJson('/api/inquiries', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('clients', [
            'email' => 'elena.rostova@example.com',
            'full_name' => 'Elena Rostova',
        ]);

        $this->assertDatabaseHas('leads', [
            'email' => 'elena.rostova@example.com',
            'source' => 'website_quote_form',
            'safari_interest' => 'Tarangire & Ngorongoro 3-Day',
        ]);
    }

    public function test_api_inquiries_rejects_payload_exceeding_20kb(): void
    {
        $largeString = str_repeat('A', 22 * 1024); // 22 KB

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->validToken)
                         ->postJson('/api/inquiries', [
                             'name' => 'Large Payload',
                             'email' => 'large@example.com',
                             'message' => $largeString,
                         ]);

        $response->assertStatus(413)
                 ->assertJson([
                     'ok' => false,
                     'error' => 'Payload too large. Maximum size is 20 KB.',
                 ]);
    }
}
