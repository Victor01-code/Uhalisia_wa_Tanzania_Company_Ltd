<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'type',
        'days',
        'summary',
        'base_price_usd',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'days' => 'integer',
            'base_price_usd' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    public function tourDays(): HasMany
    {
        return $this->hasMany(TourDay::class)->orderBy('day_number');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
