<?php

namespace App\Models;

use App\Enums\TireUsage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tire extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'tire_size_id',
        'sku',
        'name',
        'slug',
        'pattern',
        'load_index',
        'speed_rating',
        'utqg_treadwear',
        'utqg_traction',
        'utqg_temperature',
        'fuel_efficiency_grade',
        'wet_grip_grade',
        'noise_db',
        'noise_class',
        'country_of_origin',
        'usage',
        'is_runflat',
        'is_all_terrain',
        'is_highway',
        'is_winter',
        'is_summer',
        'base_price',
        'sale_price',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'load_index'        => 'integer',
        'utqg_treadwear'    => 'integer',
        'noise_db'          => 'integer',
        'is_runflat'        => 'boolean',
        'is_all_terrain'    => 'boolean',
        'is_highway'        => 'boolean',
        'is_winter'         => 'boolean',
        'is_summer'         => 'boolean',
        'is_active'         => 'boolean',
        'base_price'        => 'decimal:2',
        'sale_price'        => 'decimal:2',
        'usage'             => TireUsage::class,
    ];

    // Relationships
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function tireSize(): BelongsTo
    {
        return $this->belongsTo(TireSize::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForSize(
        Builder $query,
        int $width,
        int $aspectRatio,
        float|int $rimDiameter
    ): Builder {
        return $query->whereHas('tireSize', function (Builder $q) use (
            $width,
            $aspectRatio,
            $rimDiameter
        ) {
            $q->where('width', $width)
                ->where('aspect_ratio', $aspectRatio)
                ->where('rim_diameter', $rimDiameter);
        });
    }

    public function scopeForVehicle(Builder $query, Vehicle $vehicle): Builder
    {
        return $query->whereHas('tireSize.vehicleFitments', function (Builder $q) use ($vehicle) {
            $q->where('vehicle_id', $vehicle->id);
        });
    }

    public function scopeFilterPriceBetween(
        Builder $query,
        ?float $min,
        ?float $max
    ): Builder {
        if ($min !== null) {
            $query->where('base_price', '>=', $min);
        }

        if ($max !== null) {
            $query->where('base_price', '<=', $max);
        }

        return $query;
    }

    public function scopeForBrand(Builder $query, int $brandId): Builder
    {
        return $query->where('brand_id', $brandId);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->sale_price ?? $this->base_price;
    }
}
