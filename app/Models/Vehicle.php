<?php

namespace App\Models;

use App\Enums\VehicleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'vehicle_brand_id',
        'vehicle_line_id',
        'vehicle_version_id',
        'year',
        'type',
        'engine',
        'fuel_type',
        'body_type',
        'is_active',
    ];

    protected $casts = [
        'year'      => 'integer',
        'is_active' => 'boolean',
        'type'      => VehicleType::class,
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brand_id');
    }

    public function line(): BelongsTo
    {
        return $this->belongsTo(VehicleLine::class, 'vehicle_line_id');
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(VehicleVersion::class, 'vehicle_version_id');
    }

    public function tireFitments(): HasMany
    {
        return $this->hasMany(VehicleTireFitment::class);
    }

    public function scopeForBrandLineYear(
        Builder $query,
        int $brandId,
        int $lineId,
        int $year
    ): Builder {
        return $query->where('vehicle_brand_id', $brandId)
            ->where('vehicle_line_id', $lineId)
            ->where('year', $year);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
