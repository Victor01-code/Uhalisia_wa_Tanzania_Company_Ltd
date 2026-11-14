<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusHistory extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'status_history';

    protected $fillable = [
        'entity',
        'entity_id',
        'from_status',
        'to_status',
        'changed_by',
        'note',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public static function log(string $entity, int $entityId, ?string $fromStatus, string $toStatus, ?int $changedBy = null, ?string $note = null): self
    {
        return static::create([
            'entity' => $entity,
            'entity_id' => $entityId,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => $changedBy,
            'note' => $note,
            'created_at' => now(),
        ]);
    }
}
