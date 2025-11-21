<?php

namespace App\Models;

use App\Enums\CompanyType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'legal_name',
        'tax_id',
        'email',
        'phone',
        'website',
        'type',
        'billing_address_line1',
        'billing_address_line2',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'credit_limit',
        'payment_terms_days',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'type'              => CompanyType::class,
        'credit_limit'      => 'decimal:2',
        'payment_terms_days'=> 'integer',
        'is_active'         => 'boolean',
        'meta'              => 'array',
    ];

    // Relationships

    public function contacts(): HasMany
    {
        return $this->hasMany(CompanyContact::class);
    }

    public function fleets(): HasMany
    {
        return $this->hasMany(Fleet::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(CompanyContract::class);
    }

    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType(Builder $query, CompanyType $type): Builder
    {
        return $query->where('type', $type->value);
    }

    public function scopeWithTaxId(Builder $query, string $taxId): Builder
    {
        return $query->where('tax_id', $taxId);
    }
}
