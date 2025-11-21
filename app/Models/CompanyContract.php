<?php

namespace App\Models;

use App\Enums\CompanyContractStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'status',
        'start_date',
        'end_date',
        'credit_limit',
        'payment_terms_days',
        'notes',
        'meta',
    ];

    protected $casts = [
        'status'            => CompanyContractStatus::class,
        'start_date'        => 'date',
        'end_date'          => 'date',
        'credit_limit'      => 'decimal:2',
        'payment_terms_days'=> 'integer',
        'meta'              => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function priceRules(): HasMany
    {
        return $this->hasMany(CompanyPriceRule::class);
    }
}
