<?php

namespace App\Models;

use App\Enums\PaymentMethodType;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider',
        'reference',
        'provider_payment_id',
        'status',
        'amount',
        'currency',
        'payment_method_type',
        'provider_payload',
        'meta',
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
        'payment_method_type' => PaymentMethodType::class,
        'amount' => 'decimal:2',
        'provider_payload' => 'array',
        'meta' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function scopeForOrder(Builder $query, int $orderId): Builder
    {
        return $query->where('order_id', $orderId);
    }
}
