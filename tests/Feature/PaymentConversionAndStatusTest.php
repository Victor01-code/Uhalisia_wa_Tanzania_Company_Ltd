<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Client;
use App\Models\ExchangeRate;
use App\Models\Payment;
use App\Models\StatusHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentConversionAndStatusTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Client $client;
    protected Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Finance Admin',
            'email' => 'admin@uhalisiawatanzania.com',
            'password' => bcrypt('Secret123!'),
            'role' => 'admin',
            'active' => true,
        ]);

        $this->client = Client::create([
            'full_name' => 'Marcus Booking',
            'email' => 'marcus@example.com',
            'country' => 'Germany',
        ]);

        ExchangeRate::create([
            'year' => (int) date('Y'),
            'month' => (int) date('n'),
            'tzs_per_usd' => 2650.00,
        ]);

        $this->booking = Booking::create([
            'client_id' => $this->client->id,
            'title' => 'Marcus - 5-Day Serengeti Safari',
            'start_date' => date('Y-m-d', strtotime('+30 days')),
            'end_date' => date('Y-m-d', strtotime('+34 days')),
            'adults' => 2,
            'children' => 0,
            'status' => Booking::STATUS_INQUIRY,
            'payment_mode' => Booking::PAYMENT_MODE_COMPANY,
            'total_price_usd' => 5000.00,
            'discount_usd' => 0.00,
            'created_by' => $this->admin->id,
        ]);
    }

    // ── Currency Conversion Tests ────────────────────────────────────────────

    public function test_usd_payment_correctly_converts_to_tzs(): void
    {
        $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 1000.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'method' => 'bank',
            'reference' => 'USD-TEST-001',
            'type' => 'deposit',
            'notes' => 'Test USD payment',
        ]);

        $payment = Payment::latest()->first();
        $this->assertNotNull($payment);
        $this->assertEquals(1000.00, round($payment->amount_usd, 2));
        $this->assertEquals(2650000.00, round($payment->amount_tzs, 2));
        $this->assertEquals(2650.00, round($payment->exchange_rate_used, 2));
    }

    public function test_tzs_payment_correctly_converts_to_usd(): void
    {
        $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 2650000.00,
            'currency' => 'TZS',
            'exchange_rate_used' => 2650.00,
            'method' => 'mobile_money',
            'reference' => 'TZS-TEST-001',
            'type' => 'deposit',
            'notes' => 'Test TZS payment',
        ]);

        $payment = Payment::latest()->first();
        $this->assertNotNull($payment);
        $this->assertEquals(2650000.00, round($payment->amount_tzs, 2));
        $this->assertEquals(1000.00, round($payment->amount_usd, 2));
    }

    public function test_exchange_rate_is_stored_per_payment(): void
    {
        $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 500.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2700.00, // different from the month's standard rate
            'method' => 'cash',
            'reference' => 'RATE-TEST',
            'type' => 'deposit',
        ]);

        $payment = Payment::latest()->first();
        $this->assertEquals(2700.00, round($payment->exchange_rate_used, 2));
        $this->assertEquals(1350000.00, round($payment->amount_tzs, 2));
    }

    // ── Balance Calculation Tests ────────────────────────────────────────────

    public function test_booking_balance_decreases_after_payment(): void
    {
        $this->assertEquals(5000.00, $this->booking->balanceUsd());

        Payment::create([
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 2000.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'amount_usd' => 2000.00,
            'amount_tzs' => 5300000.00,
            'method' => 'bank',
            'type' => 'deposit',
            'received_by' => $this->admin->id,
        ]);

        $this->booking->refresh();
        $this->assertEquals(3000.00, $this->booking->balanceUsd());
    }

    public function test_refund_increases_balance(): void
    {
        // First, pay in full
        Payment::create([
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 5000.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'amount_usd' => 5000.00,
            'amount_tzs' => 13250000.00,
            'method' => 'bank',
            'type' => 'balance',
            'received_by' => $this->admin->id,
        ]);

        $this->booking->refresh();
        $this->assertTrue($this->booking->isPaidInFull());

        // Then refund
        Payment::create([
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 1000.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'amount_usd' => 1000.00,
            'amount_tzs' => 2650000.00,
            'method' => 'bank',
            'type' => 'refund',
            'received_by' => $this->admin->id,
        ]);

        $this->booking->refresh();
        $this->assertFalse($this->booking->isPaidInFull());
        $this->assertEquals(1000.00, $this->booking->balanceUsd());
    }

    public function test_booking_with_discount_calculates_net_price(): void
    {
        $this->booking->update(['discount_usd' => 500.00]);
        $this->booking->refresh();

        $this->assertEquals(4500.00, $this->booking->netPriceUsd());
        $this->assertEquals(4500.00, $this->booking->balanceUsd());
    }

    // ── Auto-Suggest Status Transition Tests ─────────────────────────────────

    public function test_deposit_suggests_confirmed_status(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 1500.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'method' => 'bank',
            'reference' => 'DEP-001',
            'type' => 'deposit',
        ]);

        $response->assertRedirect(route('admin.bookings.show', $this->booking->id));

        // Booking should still be in inquiry (not auto-transitioned unless confirmed)
        $this->booking->refresh();
        $this->assertEquals(Booking::STATUS_INQUIRY, $this->booking->status);

        // A status suggestion should be flashed
        $response->assertSessionHas('status_suggestion');
    }

    public function test_deposit_with_transition_flag_updates_to_confirmed(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 1500.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'method' => 'bank',
            'reference' => 'DEP-002',
            'type' => 'deposit',
            'transition_status' => true,
        ]);

        $this->booking->refresh();
        $this->assertEquals(Booking::STATUS_CONFIRMED, $this->booking->status);

        // Verify status history was logged
        $history = StatusHistory::where('entity', 'booking')
            ->where('entity_id', $this->booking->id)
            ->latest('created_at')
            ->first();

        $this->assertNotNull($history);
        $this->assertEquals('inquiry', $history->from_status);
        $this->assertEquals('confirmed', $history->to_status);
    }

    public function test_full_payment_with_transition_updates_to_paid_in_full(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 5000.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'method' => 'bank',
            'reference' => 'FULL-001',
            'type' => 'balance',
            'transition_status' => true,
        ]);

        $this->booking->refresh();
        $this->assertEquals(Booking::STATUS_PAID_IN_FULL, $this->booking->status);
    }

    // ── Exchange Rate Model Tests ────────────────────────────────────────────

    public function test_exchange_rate_fallback_to_previous_month(): void
    {
        // Current month already has a rate of 2650 (from setUp)
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('n');

        // Next month should fall back to current month's rate
        $nextMonth = $currentMonth === 12 ? 1 : $currentMonth + 1;
        $nextYear = $currentMonth === 12 ? $currentYear + 1 : $currentYear;

        $rate = ExchangeRate::getRateFor($nextYear, $nextMonth);
        $this->assertEquals(2650.00, $rate);
    }

    public function test_exchange_rate_default_when_no_rates_exist(): void
    {
        // Query a year far in the past with no data
        $rate = ExchangeRate::getRateFor(2000, 1);
        $this->assertEquals(2600.00, $rate); // Default fallback
    }

    // ── Report Totals Validation ─────────────────────────────────────────────

    public function test_report_totals_match_sum_of_payments(): void
    {
        // Create multiple payments
        Payment::create([
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 2000.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'amount_usd' => 2000.00,
            'amount_tzs' => 5300000.00,
            'method' => 'bank',
            'type' => 'deposit',
            'received_by' => $this->admin->id,
        ]);

        Payment::create([
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 3000.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'amount_usd' => 3000.00,
            'amount_tzs' => 7950000.00,
            'method' => 'card',
            'type' => 'balance',
            'received_by' => $this->admin->id,
        ]);

        $totalUsd = Payment::where('booking_id', $this->booking->id)
            ->where('type', '!=', 'refund')
            ->sum('amount_usd');

        $totalTzs = Payment::where('booking_id', $this->booking->id)
            ->where('type', '!=', 'refund')
            ->sum('amount_tzs');

        $this->assertEquals(5000.00, round($totalUsd, 2));
        $this->assertEquals(13250000.00, round($totalTzs, 2));

        // Verify booking model agrees
        $this->booking->refresh();
        $this->assertEquals(5000.00, round($this->booking->paidUsd(), 2));
        $this->assertTrue($this->booking->isPaidInFull());
    }

    // ── Validation Tests ─────────────────────────────────────────────────────

    public function test_payment_requires_valid_currency(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 1000.00,
            'currency' => 'EUR', // invalid
            'exchange_rate_used' => 1.10,
            'method' => 'bank',
            'type' => 'deposit',
        ]);

        $response->assertSessionHasErrors('currency');
    }

    public function test_payment_requires_positive_amount(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 0,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'method' => 'bank',
            'type' => 'deposit',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_accountant_can_create_payment(): void
    {
        $accountant = User::create([
            'name' => 'Accountant',
            'email' => 'accountant@uhalisiawatanzania.com',
            'password' => bcrypt('Secret123!'),
            'role' => 'accountant',
            'active' => true,
        ]);

        $response = $this->actingAs($accountant)->post(route('admin.payments.store'), [
            'booking_id' => $this->booking->id,
            'paid_on' => date('Y-m-d'),
            'amount' => 500.00,
            'currency' => 'USD',
            'exchange_rate_used' => 2650.00,
            'method' => 'cash',
            'type' => 'deposit',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', [
            'booking_id' => $this->booking->id,
            'amount' => 500.00,
        ]);
    }

    public function test_agent_cannot_access_payment_creation(): void
    {
        $agent = User::create([
            'name' => 'Agent',
            'email' => 'agent@uhalisiawatanzania.com',
            'password' => bcrypt('Secret123!'),
            'role' => 'agent',
            'active' => true,
        ]);

        $response = $this->actingAs($agent)->get(route('admin.payments.create'));
        $response->assertStatus(403);
    }
}
