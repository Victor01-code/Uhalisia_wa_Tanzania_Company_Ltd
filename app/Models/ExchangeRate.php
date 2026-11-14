<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'tzs_per_usd',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'month' => 'integer',
            'tzs_per_usd' => 'decimal:2',
        ];
    }

    public static function getRateFor(int $year, int $month): float
    {
        $rate = static::where('year', $year)
            ->where('month', $month)
            ->value('tzs_per_usd');

        if ($rate !== null) {
            return (float) $rate;
        }

        // Fall back to most recent previous rate
        $prev = static::where(function ($q) use ($year, $month) {
                $q->where('year', '<', $year)
                  ->orWhere(function ($q2) use ($year, $month) {
                      $q2->where('year', $year)->where('month', '<', $month);
                  });
            })
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->value('tzs_per_usd');

        return $prev ? (float) $prev : 2600.00; // Standard default Tanzanian Shilling exchange rate
    }
}
