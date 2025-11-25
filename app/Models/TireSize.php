<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TireSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'width',
        'aspect_ratio',
        'rim_diameter',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'width' => 'integer',
        'aspect_ratio' => 'integer',
        'rim_diameter' => 'float',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'label',
    ];

    // Relationships
    public function tires(): HasMany
    {
        return $this->hasMany(Tire::class);
    }

    public function vehicleFitments(): HasMany
    {
        return $this->hasMany(VehicleTireFitment::class);
    }

    // Accessors
    public function getLabelAttribute(): string
    {
        return sprintf('%d/%d R%s', $this->width, $this->aspect_ratio, $this->rim_diameter);
    }

    // Scopes
    public function scopeSize(
        Builder $query,
        int $width,
        int $aspectRatio,
        float|int $rimDiameter
    ): Builder {
        return $query->where('width', $width)
            ->where('aspect_ratio', $aspectRatio)
            ->where('rim_diameter', $rimDiameter);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
