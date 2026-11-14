<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExchangeRateController extends Controller
{
    public function index(Request $request): View
    {
        $year = (int) $request->input('year', date('Y'));

        $storedRates = ExchangeRate::where('year', $year)
            ->pluck('tzs_per_usd', 'month')
            ->toArray();

        // Calculate carried forward rates for all 12 months
        $rates = [];
        $fallback = ExchangeRate::getRateFor($year, 1);

        for ($m = 1; $m <= 12; $m++) {
            if (isset($storedRates[$m])) {
                $rates[$m] = (float) $storedRates[$m];
                $fallback = $rates[$m];
            } else {
                $rates[$m] = $fallback;
            }
        }

        $years = range((int)date('Y') - 2, (int)date('Y') + 2);

        return view('admin.exchange_rates.index', compact('year', 'rates', 'storedRates', 'years'));
    }

    public function update(Request $request): RedirectResponse
    {
        $year = (int) $request->input('year', date('Y'));
        $rates = $request->input('rates', []);

        foreach ($rates as $month => $rate) {
            if ($rate !== null && $rate !== '') {
                ExchangeRate::updateOrCreate(
                    ['year' => $year, 'month' => (int) $month],
                    ['tzs_per_usd' => (float) $rate]
                );
            }
        }

        return back()->with('success', "Exchange rates for {$year} updated successfully.");
    }
}
