<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lead;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $thirtyDaysAhead = (clone $today)->addDays(30);

        // 1. Stat Counts
        $newLeadsCount = Lead::where('status', 'new')->count();

        $upcomingTripsCount = Booking::whereIn('status', [
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_PAID_IN_FULL,
            ])
            ->whereBetween('start_date', [$today->toDateString(), $thirtyDaysAhead->toDateString()])
            ->count();

        $tripsInProgressCount = Booking::where('status', Booking::STATUS_IN_PROGRESS)->count();

        // Active bookings with unpaid balance
        $activeBookings = Booking::whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_COMPLETED])
            ->with('payments')
            ->get();

        $unpaidBalancesCount = 0;
        $totalUnpaidBalanceUsd = 0.0;
        foreach ($activeBookings as $b) {
            $balance = $b->balanceUsd();
            if ($balance > 0.01) {
                $unpaidBalancesCount++;
                $totalUnpaidBalanceUsd += $balance;
            }
        }

        // 2. Recent Leads
        $recentLeads = Lead::with('client')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // 3. Upcoming Trips (next 30 days)
        $upcomingBookings = Booking::whereIn('status', [
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_PAID_IN_FULL,
                Booking::STATUS_IN_PROGRESS,
            ])
            ->where('end_date', '>=', $today->toDateString())
            ->with(['client', 'tour'])
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();

        // 4. Recent Payments
        $recentPayments = Payment::with('booking.client')
            ->orderBy('paid_on', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'newLeadsCount',
            'upcomingTripsCount',
            'tripsInProgressCount',
            'unpaidBalancesCount',
            'totalUnpaidBalanceUsd',
            'recentLeads',
            'upcomingBookings',
            'recentPayments'
        ));
    }
}
