<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;

class ExchangeRateSeeder extends Seeder
{
    public function run(): void
    {
        $year = 2026;
        $monthlyRates = [
            1 => 2580.00,
            2 => 2590.00,
            3 => 2600.00,
            4 => 2610.00,
            5 => 2615.00,
            6 => 2620.00,
            7 => 2630.00,
            8 => 2640.00,
            9 => 2645.00,
            10 => 2650.00,
            11 => 2655.00,
            12 => 2660.00,
        ];

        foreach ($monthlyRates as $month => $rate) {
            ExchangeRate::updateOrCreate(
                ['year' => $year, 'month' => $month],
                ['tzs_per_usd' => $rate]
            );
        }
    }
}
