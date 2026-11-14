<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\ExchangeRate;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Expense::with('booking.client');

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('booking_id')) {
            $query->where('booking_id', $request->input('booking_id'));
        }

        if ($request->filled('scope')) {
            if ($request->input('scope') === 'general') {
                $query->whereNull('booking_id');
            } elseif ($request->input('scope') === 'booking') {
                $query->whereNotNull('booking_id');
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('expense_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('expense_date', '<=', $request->input('end_date'));
        }

        $allExpenses = (clone $query)->get();
        $totalTzs = $allExpenses->sum('amount_tzs');
        $totalUsd = $allExpenses->sum('amount_usd');

        $expenses = $query->orderBy('expense_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        $bookings = Booking::whereNotIn('status', [Booking::STATUS_CANCELLED])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.expenses.index', compact(
            'expenses',
            'bookings',
            'totalTzs',
            'totalUsd'
        ));
    }

    public function create(Request $request): View
    {
        $bookings = Booking::whereNotIn('status', [Booking::STATUS_CANCELLED])
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedBookingId = $request->input('booking_id');
        $expenseDate = $request->input('expense_date', date('Y-m-d'));
        $carbonDate = Carbon::parse($expenseDate);
        $currentRate = ExchangeRate::getRateFor($carbonDate->year, $carbonDate->month);

        return view('admin.expenses.create', compact('bookings', 'selectedBookingId', 'expenseDate', 'currentRate'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => ['nullable', 'exists:bookings,id'],
            'expense_date' => ['required', 'date'],
            'category' => ['required', 'in:' . implode(',', array_keys(Expense::CATEGORIES))],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'in:USD,TZS'],
            'notes' => ['nullable', 'string'],
        ]);

        $amount = (float) $validated['amount'];
        $carbonDate = Carbon::parse($validated['expense_date']);
        $rate = ExchangeRate::getRateFor($carbonDate->year, $carbonDate->month);

        if ($validated['currency'] === 'USD') {
            $amountTzs = $amount * $rate;
        } else {
            $amountTzs = $amount;
        }

        $bookingId = !empty($validated['booking_id']) ? $validated['booking_id'] : null;
        $notes = $validated['notes'] ?? null;

        $expense = Expense::create([
            'booking_id' => $bookingId,
            'expense_date' => $validated['expense_date'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'amount' => $amount,
            'currency' => $validated['currency'],
            'amount_tzs' => $amountTzs,
            'notes' => $notes,
        ]);

        AuditLog::log(
            'create_expense',
            'expense',
            $expense->id,
            "Recorded expense '{$validated['description']}' ({$amount} {$validated['currency']}) under {$validated['category']}"
        );

        if ($bookingId) {
            return redirect()->route('admin.bookings.show', $bookingId)
                ->with('success', "Expense recorded successfully for booking.");
        }

        return redirect()->route('admin.expenses.index')
            ->with('success', "Expense recorded successfully.");
    }

    public function edit(Expense $expense): View
    {
        $bookings = Booking::whereNotIn('status', [Booking::STATUS_CANCELLED])
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        $carbonDate = Carbon::parse($expense->expense_date);
        $currentRate = ExchangeRate::getRateFor($carbonDate->year, $carbonDate->month);

        return view('admin.expenses.edit', compact('expense', 'bookings', 'currentRate'));
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => ['nullable', 'exists:bookings,id'],
            'expense_date' => ['required', 'date'],
            'category' => ['required', 'in:' . implode(',', array_keys(Expense::CATEGORIES))],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'in:USD,TZS'],
            'notes' => ['nullable', 'string'],
        ]);

        $amount = (float) $validated['amount'];
        $carbonDate = Carbon::parse($validated['expense_date']);
        $rate = ExchangeRate::getRateFor($carbonDate->year, $carbonDate->month);

        if ($validated['currency'] === 'USD') {
            $amountTzs = $amount * $rate;
        } else {
            $amountTzs = $amount;
        }

        $bookingId = !empty($validated['booking_id']) ? $validated['booking_id'] : null;
        $notes = $validated['notes'] ?? null;

        $expense->update([
            'booking_id' => $bookingId,
            'expense_date' => $validated['expense_date'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'amount' => $amount,
            'currency' => $validated['currency'],
            'amount_tzs' => $amountTzs,
            'notes' => $notes,
        ]);

        AuditLog::log(
            'update_expense',
            'expense',
            $expense->id,
            "Updated expense '{$validated['description']}' ({$amount} {$validated['currency']})"
        );

        return redirect()->route('admin.expenses.index')
            ->with('success', "Expense updated successfully.");
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $desc = $expense->description;
        $id = $expense->id;

        AuditLog::log(
            'delete_expense',
            'expense',
            $id,
            "Deleted expense record '{$desc}'"
        );

        $expense->delete();

        return back()->with('success', "Expense record deleted successfully.");
    }
}
