<?php

namespace App\Models;

use App\Enums\PriceAdjustmentType;
use App\Enums\PriceTargetType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyPriceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_contract_id',
        'target_type',
        'target_id',
        'product_type',
        'adjustment_type',
        'value',
        'min_quantity',
        'is_active',
        'valid_from',
        'valid_until',
        'meta',
    ];

    protected $casts = [
        'target_type' => PriceTargetType::class,
        'adjustment_type' => PriceAdjustmentType::class,
        'value' => 'decimal:2',
        'min_quantity' => 'integer',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'meta' => 'array',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(CompanyContract::class, 'company_contract_id');
    }

    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForProductType(Builder $query, ?string $productType): Builder
    {
        if (! $productType) {
            return $query;
        }

        return $query->where('product_type', $productType);
    }
}
