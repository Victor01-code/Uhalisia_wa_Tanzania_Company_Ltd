<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\ExchangeRate;
use App\Models\Expense;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $year = (int) $request->input('year', date('Y'));
        $month = $request->filled('month') && $request->input('month') !== 'all' ? (int) $request->input('month') : null;

        AuditLog::log(
            'view_report',
            'report',
            null,
            "Viewed financial report for year {$year}" . ($month ? " month {$month}" : "")
        );

        // 1. Filtered Payments (Only company_handles bookings count in income per spec)
        $paymentsQuery = Payment::whereHas('booking', function ($q) {
            $q->where('payment_mode', 'company_handles')
              ->whereNotIn('status', [Booking::STATUS_CANCELLED]);
        })->whereYear('paid_on', $year);

        if ($month) {
            $paymentsQuery->whereMonth('paid_on', $month);
        }

        $filteredPayments = $paymentsQuery->get();
        $incomeUsd = (float) $filteredPayments->where('type', '!=', 'refund')->sum('amount_usd')
                   - (float) $filteredPayments->where('type', 'refund')->sum('amount_usd');
        $incomeTzs = (float) $filteredPayments->where('type', '!=', 'refund')->sum('amount_tzs')
                   - (float) $filteredPayments->where('type', 'refund')->sum('amount_tzs');

        // 2. Filtered Expenses
        $expensesQuery = Expense::whereYear('expense_date', $year);
        if ($month) {
            $expensesQuery->whereMonth('expense_date', $month);
        }
        $filteredExpenses = $expensesQuery->get();

        $expensesTzs = (float) $filteredExpenses->sum('amount_tzs');
        $expensesUsd = (float) $filteredExpenses->sum('amount_usd');

        // 3. Net Result
        $netUsd = $incomeUsd - $expensesUsd;
        $netTzs = $incomeTzs - $expensesTzs;
        $marginPct = $incomeUsd > 0 ? (($netUsd / $incomeUsd) * 100) : 0.0;

        // 4. Expenses by Category
        $expensesByCategory = [];
        foreach (Expense::CATEGORIES as $catKey => $catLabel) {
            $catExpenses = $filteredExpenses->where('category', $catKey);
            $catTzs = (float) $catExpenses->sum('amount_tzs');
            $catUsd = (float) $catExpenses->sum('amount_usd');
            if ($catUsd > 0 || $catTzs > 0) {
                $expensesByCategory[$catKey] = [
                    'label' => $catLabel,
                    'usd' => $catUsd,
                    'tzs' => $catTzs,
                ];
            }
        }

        // 5. Monthly breakdown for Chart.js & Table (Jan - Dec)
        $monthlyTrend = [];
        for ($m = 1; $m <= 12; $m++) {
            $mPayments = Payment::whereHas('booking', function ($q) {
                $q->where('payment_mode', 'company_handles')
                  ->whereNotIn('status', [Booking::STATUS_CANCELLED]);
            })->whereYear('paid_on', $year)->whereMonth('paid_on', $m)->get();

            $mIncomeUsd = (float) $mPayments->where('type', '!=', 'refund')->sum('amount_usd')
                        - (float) $mPayments->where('type', 'refund')->sum('amount_usd');
            $mIncomeTzs = (float) $mPayments->where('type', '!=', 'refund')->sum('amount_tzs')
                        - (float) $mPayments->where('type', 'refund')->sum('amount_tzs');

            $mExpenses = Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $m)->get();
            $mExpTzs = (float) $mExpenses->sum('amount_tzs');
            $mExpUsd = (float) $mExpenses->sum('amount_usd');

            $mNetUsd = $mIncomeUsd - $mExpUsd;
            $mNetTzs = $mIncomeTzs - $mExpTzs;
            $mMargin = $mIncomeUsd > 0 ? (($mNetUsd / $mIncomeUsd) * 100) : 0.0;

            $monthName = Carbon::createFromDate($year, $m, 1)->format('M');

            $monthlyTrend[$m] = [
                'month_name' => $monthName,
                'income_usd' => $mIncomeUsd,
                'income_tzs' => $mIncomeTzs,
                'expenses_usd' => $mExpUsd,
                'expenses_tzs' => $mExpTzs,
                'net_usd' => $mNetUsd,
                'net_tzs' => $mNetTzs,
                'margin_pct' => $mMargin,
            ];
        }

        // 6. Per-Booking Profitability
        $bookingsQuery = Booking::with(['client', 'tour', 'payments', 'expenses'])
            ->whereNotIn('status', [Booking::STATUS_CANCELLED])
            ->whereYear('start_date', $year);

        if ($month) {
            $bookingsQuery->whereMonth('start_date', $month);
        }

        $bookingsProfit = $bookingsQuery->orderBy('start_date', 'asc')->get()->map(function ($b) {
            $revenueUsd = $b->paidUsd();
            $costsUsd = (float) $b->expenses->sum('amount_usd');
            $profitUsd = $revenueUsd - $costsUsd;
            $margin = $revenueUsd > 0 ? (($profitUsd / $revenueUsd) * 100) : 0.0;

            return [
                'booking' => $b,
                'quoted_usd' => $b->netPriceUsd(),
                'revenue_usd' => $revenueUsd,
                'costs_usd' => $costsUsd,
                'profit_usd' => $profitUsd,
                'margin_pct' => $margin,
                'balance_usd' => $b->balanceUsd(),
            ];
        });

        // 7. Outstanding Balances
        $outstandingBookings = Booking::with(['client', 'payments'])
            ->whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_COMPLETED])
            ->get()
            ->filter(fn($b) => $b->balanceUsd() > 0.01)
            ->sortByDesc(fn($b) => $b->balanceUsd());

        $totalOutstandingUsd = $outstandingBookings->sum(fn($b) => $b->balanceUsd());

        // 8. Bookings by Tour breakdown
        $tours = Tour::withCount(['bookings' => function ($q) use ($year, $month) {
            $q->whereNotIn('status', [Booking::STATUS_CANCELLED])
              ->whereYear('start_date', $year);
            if ($month) {
                $q->whereMonth('start_date', $month);
            }
        }])->get();

        // 9. Leads by Source & Conversion Rate
        $leadsQuery = Lead::whereYear('created_at', $year);
        if ($month) {
            $leadsQuery->whereMonth('created_at', $month);
        }
        $allLeads = $leadsQuery->get();
        $totalLeads = $allLeads->count();
        $convertedLeads = $allLeads->where('status', 'converted')->count();
        $conversionRate = $totalLeads > 0 ? (($convertedLeads / $totalLeads) * 100) : 0.0;

        $leadsBySource = [];
        foreach ($allLeads->groupBy('source') as $src => $srcLeads) {
            $srcTotal = $srcLeads->count();
            $srcConverted = $srcLeads->where('status', 'converted')->count();
            $leadsBySource[$src] = [
                'total' => $srcTotal,
                'converted' => $srcConverted,
                'rate' => $srcTotal > 0 ? (($srcConverted / $srcTotal) * 100) : 0.0,
            ];
        }

        $years = range((int)date('Y') - 2, (int)date('Y') + 2);

        return view('admin.reports.index', compact(
            'year',
            'month',
            'years',
            'incomeUsd',
            'incomeTzs',
            'expensesUsd',
            'expensesTzs',
            'netUsd',
            'netTzs',
            'marginPct',
            'expensesByCategory',
            'monthlyTrend',
            'bookingsProfit',
            'outstandingBookings',
            'totalOutstandingUsd',
            'tours',
            'totalLeads',
            'convertedLeads',
            'conversionRate',
            'leadsBySource'
        ));
    }

    public function exportMonthly(Request $request): StreamedResponse
    {
        $year = (int) $request->input('year', date('Y'));

        AuditLog::log(
            'export_report',
            'report',
            null,
            "Exported monthly financial report spreadsheet for year {$year}"
        );

        $filename = "UWT-Monthly-Financial-Report-{$year}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"; filename*=UTF-8''" . rawurlencode($filename),
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return Response::stream(function () use ($year) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens with proper character encoding
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Uhalisia wa Tanzania Company Ltd - Monthly Financial Ledger']);
            fputcsv($handle, ["Calendar Year: {$year}"]);
            fputcsv($handle, ['Generated: ' . date('Y-m-d H:i:s')]);
            fputcsv($handle, []);

            fputcsv($handle, [
                'Month',
                'Income (USD)',
                'Income (TZS)',
                'Expenses (USD)',
                'Expenses (TZS)',
                'Net Result (USD)',
                'Net Result (TZS)',
                'Profit Margin (%)'
            ]);

            $totIncomeUsd = 0; $totIncomeTzs = 0;
            $totExpUsd = 0;    $totExpTzs = 0;

            for ($m = 1; $m <= 12; $m++) {
                $mPayments = Payment::whereHas('booking', function ($q) {
                    $q->where('payment_mode', 'company_handles')
                      ->whereNotIn('status', [Booking::STATUS_CANCELLED]);
                })->whereYear('paid_on', $year)->whereMonth('paid_on', $m)->get();

                $mIncomeUsd = (float) $mPayments->where('type', '!=', 'refund')->sum('amount_usd')
                            - (float) $mPayments->where('type', 'refund')->sum('amount_usd');
                $mIncomeTzs = (float) $mPayments->where('type', '!=', 'refund')->sum('amount_tzs')
                            - (float) $mPayments->where('type', 'refund')->sum('amount_tzs');

                $mExpenses = Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $m)->get();
                $mExpTzs = (float) $mExpenses->sum('amount_tzs');
                $mExpUsd = (float) $mExpenses->sum('amount_usd');

                $mNetUsd = $mIncomeUsd - $mExpUsd;
                $mNetTzs = $mIncomeTzs - $mExpTzs;
                $mMargin = $mIncomeUsd > 0 ? (($mNetUsd / $mIncomeUsd) * 100) : 0.0;

                $totIncomeUsd += $mIncomeUsd;
                $totIncomeTzs += $mIncomeTzs;
                $totExpUsd += $mExpUsd;
                $totExpTzs += $mExpTzs;

                $monthName = Carbon::createFromDate($year, $m, 1)->format('F');

                fputcsv($handle, [
                    $monthName,
                    number_format($mIncomeUsd, 2, '.', ''),
                    number_format($mIncomeTzs, 2, '.', ''),
                    number_format($mExpUsd, 2, '.', ''),
                    number_format($mExpTzs, 2, '.', ''),
                    number_format($mNetUsd, 2, '.', ''),
                    number_format($mNetTzs, 2, '.', ''),
                    number_format($mMargin, 1, '.', '') . '%'
                ]);
            }

            $totNetUsd = $totIncomeUsd - $totExpUsd;
            $totNetTzs = $totIncomeTzs - $totExpTzs;
            $totMargin = $totIncomeUsd > 0 ? (($totNetUsd / $totIncomeUsd) * 100) : 0.0;

            fputcsv($handle, []);
            fputcsv($handle, [
                'FULL YEAR TOTAL',
                number_format($totIncomeUsd, 2, '.', ''),
                number_format($totIncomeTzs, 2, '.', ''),
                number_format($totExpUsd, 2, '.', ''),
                number_format($totExpTzs, 2, '.', ''),
                number_format($totNetUsd, 2, '.', ''),
                number_format($totNetTzs, 2, '.', ''),
                number_format($totMargin, 1, '.', '') . '%'
            ]);

            fclose($handle);
        }, 200, $headers);
    }

    public function exportBookingsProfit(Request $request): StreamedResponse
    {
        $year = (int) $request->input('year', date('Y'));

        AuditLog::log(
            'export_report',
            'report',
            null,
            "Exported safari booking profitability report for year {$year}"
        );

        $filename = "UWT-Safari-Profitability-{$year}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"; filename*=UTF-8''" . rawurlencode($filename),
        ];

        return Response::stream(function () use ($year) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Uhalisia wa Tanzania Company Ltd - Safari Expedition Profitability']);
            fputcsv($handle, ["Year: {$year}"]);
            fputcsv($handle, []);

            fputcsv($handle, [
                'Reference',
                'Client Name',
                'Tour Title',
                'Start Date',
                'End Date',
                'Status',
                'Quoted Net USD',
                'Revenue Collected USD',
                'Direct Expenses USD',
                'Net Profit USD',
                'Margin (%)',
                'Outstanding Balance USD'
            ]);

            $bookings = Booking::with(['client', 'tour', 'payments', 'expenses'])
                ->whereNotIn('status', [Booking::STATUS_CANCELLED])
                ->whereYear('start_date', $year)
                ->orderBy('start_date', 'asc')
                ->get();

            foreach ($bookings as $b) {
                $revenueUsd = $b->paidUsd();
                $costsUsd = (float) $b->expenses->sum('amount_usd');
                $profitUsd = $revenueUsd - $costsUsd;
                $margin = $revenueUsd > 0 ? (($profitUsd / $revenueUsd) * 100) : 0.0;

                fputcsv($handle, [
                    $b->reference,
                    $b->client->full_name ?? 'N/A',
                    $b->title,
                    $b->start_date ? $b->start_date->format('Y-m-d') : '',
                    $b->end_date ? $b->end_date->format('Y-m-d') : '',
                    ucfirst(str_replace('_', ' ', $b->status)),
                    number_format($b->netPriceUsd(), 2, '.', ''),
                    number_format($revenueUsd, 2, '.', ''),
                    number_format($costsUsd, 2, '.', ''),
                    number_format($profitUsd, 2, '.', ''),
                    number_format($margin, 1, '.', '') . '%',
                    number_format($b->balanceUsd(), 2, '.', '')
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function exportPayments(Request $request): StreamedResponse
    {
        $year = (int) $request->input('year', date('Y'));

        AuditLog::log(
            'export_report',
            'report',
            null,
            "Exported payments ledger for year {$year}"
        );

        $filename = "UWT-Payments-Master-Ledger-{$year}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"; filename*=UTF-8''" . rawurlencode($filename),
        ];

        return Response::stream(function () use ($year) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Uhalisia wa Tanzania Company Ltd - Master Payments Ledger']);
            fputcsv($handle, ["Year: {$year}"]);
            fputcsv($handle, []);

            fputcsv($handle, [
                'Payment Date',
                'Booking Reference',
                'Client Name',
                'Payment Type',
                'Payment Method',
                'Reference Code',
                'Amount (Original)',
                'Currency',
                'Exchange Rate (TZS/USD)',
                'Amount USD',
                'Amount TZS',
                'Received By',
                'Notes'
            ]);

            $payments = Payment::with(['booking.client', 'receiver'])
                ->whereYear('paid_on', $year)
                ->orderBy('paid_on', 'desc')
                ->get();

            foreach ($payments as $p) {
                fputcsv($handle, [
                    $p->paid_on ? $p->paid_on->format('Y-m-d') : '',
                    $p->booking->reference ?? 'N/A',
                    $p->booking->client->full_name ?? 'N/A',
                    ucfirst($p->type),
                    ucfirst($p->method),
                    $p->reference ?: '',
                    number_format($p->amount, 2, '.', ''),
                    $p->currency,
                    number_format($p->exchange_rate_used, 2, '.', ''),
                    number_format($p->amount_usd, 2, '.', ''),
                    number_format($p->amount_tzs, 2, '.', ''),
                    $p->receiver->name ?? 'Staff',
                    $p->notes ?: ''
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function auditLogs(Request $request): View
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('admin.reports.audit_logs', compact('logs'));
    }
}
