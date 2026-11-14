<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_INQUIRY = 'inquiry';
    public const STATUS_QUOTE_SENT = 'quote_sent';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PAID_IN_FULL = 'paid_in_full';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_INQUIRY,
        self::STATUS_QUOTE_SENT,
        self::STATUS_CONFIRMED,
        self::STATUS_PAID_IN_FULL,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    public const PAYMENT_MODE_COMPANY = 'company_handles';
    public const PAYMENT_MODE_DIRECT = 'client_direct';

    protected $fillable = [
        'reference',
        'client_id',
        'lead_id',
        'tour_id',
        'title',
        'start_date',
        'end_date',
        'adults',
        'children',
        'status',
        'payment_mode',
        'total_price_usd',
        'discount_usd',
        'notes',
        'public_token',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'adults' => 'integer',
            'children' => 'integer',
            'total_price_usd' => 'decimal:2',
            'discount_usd' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->reference)) {
                $startDate = $booking->start_date ? Carbon::parse($booking->start_date) : now();
                $booking->reference = static::generateReference($startDate);
            }
            if (empty($booking->public_token)) {
                $booking->public_token = Str::random(64);
            }
        });
    }

    public static function generateReference(Carbon $date): string
    {
        $year = $date->format('Y');
        $prefix = "UWT-{$year}-";

        // Find highest sequence for this year
        $last = static::withTrashed()
            ->where('reference', 'LIKE', "{$prefix}%")
            ->orderBy('reference', 'desc')
            ->first();

        if ($last && preg_match('/UWT-\d{4}-(\d+)/', $last->reference, $matches)) {
            $nextSeq = (int) $matches[1] + 1;
        } else {
            $nextSeq = 1;
        }

        return $prefix . str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bookingDays(): HasMany
    {
        return $this->hasMany(BookingDay::class)->orderBy('day_number');
    }

    public function travelers(): HasMany
    {
        return $this->hasMany(Traveler::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->orderBy('paid_on', 'desc');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class)->orderBy('expense_date', 'desc');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // Financial calculations
    public function netPriceUsd(): float
    {
        return max(0.0, (float) $this->total_price_usd - (float) $this->discount_usd);
    }

    public function paidUsd(): float
    {
        return (float) $this->payments()->where('type', '!=', 'refund')->sum('amount_usd')
             - (float) $this->payments()->where('type', 'refund')->sum('amount_usd');
    }

    public function balanceUsd(): float
    {
        return max(0.0, $this->netPriceUsd() - $this->paidUsd());
    }

    public function isPaidInFull(): bool
    {
        return $this->netPriceUsd() > 0 && $this->balanceUsd() <= 0.001;
    }

    public function durationDays(): int
    {
        if ($this->start_date && $this->end_date) {
            return (int) $this->start_date->diffInDays($this->end_date) + 1;
        }
        return 1;
    }
}
