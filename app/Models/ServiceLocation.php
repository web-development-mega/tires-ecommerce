<?php

namespace App\Models;

use App\Enums\MetroMunicipality;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ServiceLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'phone',
        'email',
        'whatsapp',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'opening_hours',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'opening_hours' => 'array',
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (ServiceLocation $location): void {
            // Default region: Antioquia, Colombia
            if (empty($location->state)) {
                $location->state = 'Antioquia';
            }

            if (empty($location->country)) {
                $location->country = 'CO';
            }
        });
    }

    // Relationships

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'service_location_id');
    }

    public function serviceTypes(): BelongsToMany
    {
        return $this->belongsToMany(ServiceType::class, 'service_location_service_type')
            ->withTimestamps();
    }

    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter locations by municipality (Área Metropolitana only).
     *
     * Accepts either:
     * - Full name: "Medellín"
     * - Slug/normalized: "medellin", "la-estrella", etc.
     */
    public function scopeForMunicipality(Builder $query, ?string $municipality): Builder
    {
        if (! $municipality) {
            return $query;
        }

        $normalized = Str::of($municipality)->lower()->slug(' ')->value();

        $map = collect(MetroMunicipality::cases())
            ->mapWithKeys(function (MetroMunicipality $m) {
                $key = Str::of($m->value)->lower()->slug(' ')->value();

                return [$key => $m->value];
            });

        $value = $map[$normalized] ?? $municipality;

        return $query->where('city', $value);
    }

    public function scopeWithServiceTypeSlug(Builder $query, ?string $slug): Builder
    {
        if (! $slug) {
            return $query;
        }

        return $query->whereHas('serviceTypes', function (Builder $q) use ($slug) {
            $q->where('slug', $slug);
        });
    }
}
