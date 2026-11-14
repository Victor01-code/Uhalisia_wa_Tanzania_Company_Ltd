<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plate_number',
        'type',
        'seats',
        'active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'seats' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'resource_id')->where('resource_type', 'vehicle');
    }
}
