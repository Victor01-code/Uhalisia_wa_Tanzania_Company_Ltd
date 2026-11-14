<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'fuel' => 'Fuel & Oil',
        'driver' => 'Driver & Guide Allowance',
        'park_fees' => 'National Park Fees',
        'accommodation' => 'Lodges & Camps',
        'vehicle_service' => 'Vehicle Service & Repairs',
        'vehicle_checkup' => 'Vehicle Inspection & Checkup',
        'insurance' => 'Insurance',
        'licences' => 'Permits & Licences',
        'tax' => 'Government Tax & Levies',
        'other' => 'Other Expenses',
    ];

    protected $fillable = [
        'booking_id',
        'expense_date',
        'category',
        'description',
        'amount',
        'currency',
        'amount_tzs',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount' => 'decimal:2',
            'amount_tzs' => 'decimal:2',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getAmountUsdAttribute(): float
    {
        if ($this->currency === 'USD') {
            return (float) $this->amount;
        }

        $year = $this->expense_date ? (int)$this->expense_date->format('Y') : (int)date('Y');
        $month = $this->expense_date ? (int)$this->expense_date->format('n') : (int)date('n');
        $rate = ExchangeRate::getRateFor($year, $month);

        return $rate > 0 ? ((float) $this->amount_tzs / $rate) : 0.0;
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }
}
