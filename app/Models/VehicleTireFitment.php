<?php

namespace App\Models;

use App\Enums\FitmentPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleTireFitment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'tire_size_id',
        'position',
        'is_oem',
        'is_primary',
        'min_load_index',
        'min_speed_rating',
        'notes',
    ];

    protected $casts = [
        'is_oem' => 'boolean',
        'is_primary' => 'boolean',
        'min_load_index' => 'integer',
        'position' => FitmentPosition::class,
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function tireSize(): BelongsTo
    {
        return $this->belongsTo(TireSize::class);
    }
}
