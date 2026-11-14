<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Lead;
use App\Models\StatusHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $agent;

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
    }

    public function test_agent_can_update_lead_status_and_it_is_logged_in_history(): void
    {
        $lead = Lead::create([
            'name' => 'David Copperfield',
            'email' => 'david@example.com',
            'country' => 'Canada',
            'safari_interest' => 'Migration River Crossing',
            'status' => 'new',
            'source' => 'manual',
        ]);

        $response = $this->actingAs($this->agent)
                         ->patch("/admin/leads/{$lead->id}/status", [
                             'status' => 'contacted',
                             'note' => 'Called client on WhatsApp and discussed travel dates.',
                         ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('contacted', $lead->fresh()->status);

        // Verify status_history was logged
        $this->assertDatabaseHas('status_history', [
            'entity' => 'lead',
            'entity_id' => $lead->id,
            'from_status' => 'new',
            'to_status' => 'contacted',
            'changed_by' => $this->agent->id,
            'note' => 'Called client on WhatsApp and discussed travel dates.',
        ]);
    }

    public function test_convert_to_booking_creates_client_and_marks_lead_won(): void
    {
        // Unconverted lead without existing client
        $lead = Lead::create([
            'name' => 'Alice Walker',
            'email' => 'alice@example.com',
            'country' => 'Australia',
            'safari_interest' => 'Serengeti & Zanzibar Combo',
            'status' => 'quoted',
            'source' => 'website_quote_form',
        ]);

        $this->assertNull($lead->client_id);

        $response = $this->actingAs($this->agent)
                         ->post("/admin/leads/{$lead->id}/convert");

        $lead->refresh();

        // 1. Client was created
        $this->assertNotNull($lead->client_id);
        $client = Client::find($lead->client_id);
        $this->assertEquals('alice@example.com', $client->email);
        $this->assertEquals('Alice Walker', $client->full_name);

        // 2. Lead was marked as won
        $this->assertEquals('won', $lead->status);

        // 3. Status history logged conversion
        $this->assertDatabaseHas('status_history', [
            'entity' => 'lead',
            'entity_id' => $lead->id,
            'from_status' => 'quoted',
            'to_status' => 'won',
            'changed_by' => $this->agent->id,
        ]);

        // 4. Redirects to booking create screen with prefilled params
        $response->assertRedirect(route('admin.bookings.create', [
            'lead_id' => $lead->id,
            'client_id' => $client->id,
        ]));
    }
}
