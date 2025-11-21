<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FleetVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'fleet_id',
        'vehicle_id',
        'alias',
        'license_plate',
        'vin',
        'year',
        'meta',
    ];

    protected $casts = [
        'year' => 'integer',
        'meta' => 'array',
    ];

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
