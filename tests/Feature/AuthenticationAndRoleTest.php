<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationAndRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'Managing Director',
            'email' => 'admin@uhalisiawatanzania.com',
            'password' => Hash::make('AdminSecret123!'),
            'role' => 'admin',
            'active' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@uhalisiawatanzania.com',
            'password' => 'AdminSecret123!',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_deactivated_staff_cannot_login(): void
    {
        User::create([
            'name' => 'Former Employee',
            'email' => 'former@uhalisiawatanzania.com',
            'password' => Hash::make('Password123!'),
            'role' => 'agent',
            'active' => false, // deactivated
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'former@uhalisiawatanzania.com',
            'password' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_role_can_access_restricted_settings_and_user_management(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Secret123!'),
            'role' => 'admin',
            'active' => true,
        ]);

        $this->actingAs($admin)
             ->get('/admin/settings')
             ->assertStatus(200);

        $this->actingAs($admin)
             ->get('/admin/users')
             ->assertStatus(200);
    }

    public function test_agent_role_is_forbidden_from_settings_and_user_management(): void
    {
        $agent = User::create([
            'name' => 'Tour Consultant',
            'email' => 'consultant@example.com',
            'password' => Hash::make('Secret123!'),
            'role' => 'agent',
            'active' => true,
        ]);

        $this->actingAs($agent)
             ->get('/admin/settings')
             ->assertStatus(403);

        $this->actingAs($agent)
             ->get('/admin/users')
             ->assertStatus(403);

        // But agent CAN access leads and clients
        $this->actingAs($agent)
             ->get('/admin/leads')
             ->assertStatus(200);
    }

    public function test_accountant_role_is_forbidden_from_leads_and_settings_but_can_access_finance(): void
    {
        $accountant = User::create([
            'name' => 'Accountant User',
            'email' => 'accountant@example.com',
            'password' => Hash::make('Secret123!'),
            'role' => 'accountant',
            'active' => true,
        ]);

        $this->actingAs($accountant)
             ->get('/admin/leads')
             ->assertStatus(403);

        $this->actingAs($accountant)
             ->get('/admin/settings')
             ->assertStatus(403);

        // Accountant CAN access payments and expenses
        $this->actingAs($accountant)
             ->get('/admin/payments')
             ->assertStatus(200);

        $this->actingAs($accountant)
             ->get('/admin/expenses')
             ->assertStatus(200);
    }
}
