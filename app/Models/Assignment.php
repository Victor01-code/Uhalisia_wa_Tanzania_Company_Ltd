<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'resource_type',
        'resource_id',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function resource()
    {
        return match ($this->resource_type) {
            'vehicle' => Vehicle::find($this->resource_id),
            'driver' => Driver::find($this->resource_id),
            'guide' => Guide::find($this->resource_id),
            default => null,
        };
    }

    public static function findOverlap(string $resourceType, int $resourceId, string $startDate, string $endDate, ?int $excludeId = null): ?self
    {
        $query = static::where('resource_type', $resourceType)
            ->where('resource_id', $resourceId)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where('start_date', '<=', $endDate)
                  ->where('end_date', '>=', $startDate);
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->with('booking')->first();
    }
}
