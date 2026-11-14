<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ExchangeRate;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with(['booking.client', 'receiver'])
            ->orderBy('paid_on', 'desc')
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request): View
    {
        $bookings = Booking::whereNotIn('status', [Booking::STATUS_CANCELLED])
            ->with(['client', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedBookingId = $request->input('booking_id');
        $selectedBooking = $selectedBookingId ? Booking::with('payments')->find($selectedBookingId) : null;

        $paidOn = $request->input('paid_on', date('Y-m-d'));
        $carbonPaidOn = Carbon::parse($paidOn);
        $currentRate = ExchangeRate::getRateFor($carbonPaidOn->year, $carbonPaidOn->month);

        return view('admin.payments.create', compact('bookings', 'selectedBookingId', 'selectedBooking', 'currentRate', 'paidOn'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,id'],
            'paid_on' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'in:USD,TZS'],
            'exchange_rate_used' => ['required', 'numeric', 'min:1'],
            'method' => ['required', 'in:cash,bank,mobile_money,card,other'],
            'reference' => ['nullable', 'string', 'max:100'],
            'type' => ['required', 'in:deposit,balance,refund,other'],
            'notes' => ['nullable', 'string'],
            'transition_status' => ['nullable', 'boolean'],
        ]);

        $amount = (float) $validated['amount'];
        $rate = (float) $validated['exchange_rate_used'];

        if ($validated['currency'] === 'USD') {
            $amountUsd = $amount;
            $amountTzs = $amount * $rate;
        } else {
            $amountTzs = $amount;
            $amountUsd = $rate > 0 ? ($amount / $rate) : 0;
        }

        $payment = Payment::create([
            'booking_id' => $validated['booking_id'],
            'paid_on' => $validated['paid_on'],
            'amount' => $amount,
            'currency' => $validated['currency'],
            'exchange_rate_used' => $rate,
            'amount_usd' => $amountUsd,
            'amount_tzs' => $amountTzs,
            'method' => $validated['method'],
            'reference' => $validated['reference'] ?? null,
            'received_by' => Auth::id(),
            'notes' => $validated['notes'] ?? null,
            'type' => $validated['type'],
        ]);

        \App\Models\AuditLog::log(
            'create_payment',
            'payment',
            $payment->id,
            "Recorded payment of {$amount} {$validated['currency']} (\${$amountUsd} USD) for Booking #{$validated['booking_id']}"
        );

        $booking = Booking::with('payments')->find($validated['booking_id']);
        $newBalance = $booking->balanceUsd();

        // 4.2: Auto-suggest & confirm status transition
        $statusUpdated = false;
        $suggestedStatus = null;

        if ($validated['type'] === 'deposit' && in_array($booking->status, [Booking::STATUS_INQUIRY, Booking::STATUS_QUOTE_SENT])) {
            $suggestedStatus = Booking::STATUS_CONFIRMED;
        } elseif ($newBalance <= 0.001 && in_array($booking->status, [Booking::STATUS_INQUIRY, Booking::STATUS_QUOTE_SENT, Booking::STATUS_CONFIRMED])) {
            $suggestedStatus = Booking::STATUS_PAID_IN_FULL;
        }

        if ($suggestedStatus && $request->boolean('transition_status')) {
            $oldStatus = $booking->status;
            $booking->status = $suggestedStatus;
            $booking->save();

            \App\Models\StatusHistory::log(
                'booking',
                $booking->id,
                $oldStatus,
                $suggestedStatus,
                Auth::id(),
                "Status automatically updated upon receipt of " . ucfirst($validated['type']) . " payment (\${$amountUsd} USD)."
            );
            $statusUpdated = true;
        }

        $message = "Payment of $" . number_format($amountUsd, 2) . " USD recorded successfully.";
        if ($statusUpdated) {
            $message .= " Booking status updated to " . ucfirst(str_replace('_', ' ', $suggestedStatus)) . ".";
        } elseif ($suggestedStatus) {
            session()->flash('status_suggestion', [
                'booking_id' => $booking->id,
                'status' => $suggestedStatus,
                'label' => ucfirst(str_replace('_', ' ', $suggestedStatus)),
                'message' => $suggestedStatus === Booking::STATUS_PAID_IN_FULL
                    ? "Trip is now fully paid! Would you like to advance status to Paid in Full?"
                    : "Deposit received! Would you like to advance status to Confirmed?",
            ]);
        }

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', $message);
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $bookingId = $payment->booking_id;
        $amountUsd = $payment->amount_usd;

        \App\Models\AuditLog::log(
            'delete_payment',
            'payment',
            $payment->id,
            "Deleted payment record of \${$amountUsd} USD on Booking #{$bookingId}"
        );

        $payment->delete();

        return back()->with('success', "Payment record of $" . number_format($amountUsd, 2) . " USD removed successfully.");
    }
}
