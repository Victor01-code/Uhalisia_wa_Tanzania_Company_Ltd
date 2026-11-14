<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'day_number',
        'date',
        'title',
        'description',
        'destination',
        'accommodation',
        'meals',
        'activities',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'day_number' => 'integer',
            'date' => 'date',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
