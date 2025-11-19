<?php

namespace App\Models;

use App\Enums\CartStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'status',
        'currency',
        'subtotal',
        'discount_total',
        'grand_total',
        'items_count',
        'meta',
    ];

    protected $casts = [
        'status'         => CartStatus::class,
        'subtotal'       => 'decimal:2',
        'discount_total' => 'decimal:2',
        'grand_total'    => 'decimal:2',
        'items_count'    => 'integer',
        'meta'           => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Cart $cart): void {
            if (empty($cart->token)) {
                $cart->token = (string) Str::uuid();
            }

            if (empty($cart->status)) {
                $cart->status = CartStatus::ACTIVE;
            }

            if (empty($cart->currency)) {
                $cart->currency = 'COP';
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', CartStatus::ACTIVE);
    }

    public function scopeForToken(Builder $query, string $token): Builder
    {
        return $query->where('token', $token);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
