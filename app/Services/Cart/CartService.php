<?php

namespace App\Services\Cart;

use App\Enums\CartStatus;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Tire;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function __construct(
        private readonly int $defaultPerPage = 20
    ) {
    }

    /**
     * Resolve or create an active cart for given token and/or user.
     * - If token exists => return that cart (and optionally attach user).
     * - Else, if user has an active cart => return it.
     * - Else, create a new cart (optionally bound to user).
     */
    public function getOrCreateCart(?string $token = null, ?User $user = null): Cart
    {
        // 1. Try via token
        if ($token) {
            $cart = Cart::query()
                ->active()
                ->forToken($token)
                ->with('items.buyable')
                ->first();

            if ($cart) {
                if ($user && ! $cart->user_id) {
                    $cart->user_id = $user->id;
                    $cart->save();
                }

                return $cart;
            }
        }

        // 2. Try via user
        if ($user) {
            $cart = Cart::query()
                ->active()
                ->forUser($user->id)
                ->with('items.buyable')
                ->first();

            if ($cart) {
                return $cart;
            }
        }

        // 3. Create a new cart
        $cart = new Cart([
            'user_id' => $user?->id,
        ]);

        $cart->save();

        return $cart->fresh('items.buyable');
    }

    /**
     * Add a Tire to cart (or increase quantity if already present).
     */
    public function addTireToCart(
        Cart $cart,
        Tire $tire,
        int $quantity = 1
    ): Cart {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Quantity must be at least 1.');
        }

        return DB::transaction(function () use ($cart, $tire, $quantity): Cart {
            /** @var CartItem|null $item */
            $item = $cart->items()
                ->where('buyable_type', Tire::class)
                ->where('buyable_id', $tire->id)
                ->first();

            $unitPrice = (float) $tire->effective_price;

            if ($item) {
                $item->quantity += $quantity;
                $item->unit_price = $unitPrice; // optional: keep last price or preserve first
            } else {
                $item = new CartItem([
                    'buyable_type'   => Tire::class,
                    'buyable_id'     => $tire->id,
                    'quantity'       => $quantity,
                    'unit_price'     => $unitPrice,
                    'discount_amount'=> 0,
                    'total'          => 0, // will be recalculated
                    'meta'           => [
                        'label' => $tire->name,
                        'size'  => $tire->tireSize?->label,
                    ],
                ]);

                $cart->items()->save($item);
            }

            $this->recalculateItem($item);
            $this->recalculateCartTotals($cart);

            return $cart->fresh('items.buyable');
        });
    }

    /**
     * Update item quantity.
     */
    public function updateItemQuantity(CartItem $item, int $quantity): Cart
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Quantity must be at least 1.');
        }

        return DB::transaction(function () use ($item, $quantity): Cart {
            $item->quantity = $quantity;
            $this->recalculateItem($item);

            $cart = $item->cart;
            $this->recalculateCartTotals($cart);

            return $cart->fresh('items.buyable');
        });
    }

    /**
     * Remove an item from cart.
     */
    public function removeItem(CartItem $item): Cart
    {
        return DB::transaction(function () use ($item): Cart {
            $cart = $item->cart;

            $item->delete();

            $this->recalculateCartTotals($cart);

            return $cart->fresh('items.buyable');
        });
    }

    /**
     * Attach guest cart (by token) to user cart on login.
     * - If user has no active cart => guest cart becomes user's cart.
     * - If user already has cart => merge guest into user cart.
     */
    public function attachUserCart(User $user, ?string $guestCartToken): ?Cart
    {
        if (! $guestCartToken) {
            return $this->getActiveCartForUser($user);
        }

        /** @var Cart|null $guestCart */
        $guestCart = Cart::query()
            ->active()
            ->forToken($guestCartToken)
            ->whereNull('user_id')
            ->with('items')
            ->first();

        if (! $guestCart) {
            return $this->getActiveCartForUser($user);
        }

        /** @var Cart|null $userCart */
        $userCart = $this->getActiveCartForUser($user);

        return DB::transaction(function () use ($user, $guestCart, $userCart): Cart {
            if (! $userCart) {
                // Reuse guest cart as user cart
                $guestCart->user_id = $user->id;
                $guestCart->save();

                return $guestCart->fresh('items.buyable');
            }

            // Merge guest → user cart
            $this->mergeCarts($guestCart, $userCart);

            // Mark guest cart as abandoned
            $guestCart->status = CartStatus::ABANDONED;
            $guestCart->save();

            return $userCart->fresh('items.buyable');
        });
    }

    /**
     * Merge items from source cart into target cart.
     * Items with same buyable_type + buyable_id will be merged (quantity sum).
     */
    public function mergeCarts(Cart $source, Cart $target): void
    {
        /** @var \Illuminate\Support\Collection<int, CartItem> $sourceItems */
        $sourceItems = $source->items()->get();

        foreach ($sourceItems as $sourceItem) {
            /** @var CartItem|null $targetItem */
            $targetItem = $target->items()
                ->where('buyable_type', $sourceItem->buyable_type)
                ->where('buyable_id', $sourceItem->buyable_id)
                ->first();

            if ($targetItem) {
                $targetItem->quantity += $sourceItem->quantity;

                // Opcional: combinar descuentos / precios según reglas
                $this->recalculateItem($targetItem);
                $targetItem->save();
            } else {
                $newItem = $sourceItem->replicate([
                    'cart_id',
                    'created_at',
                    'updated_at',
                ]);
                $newItem->cart_id = $target->id;
                $newItem->save();
            }
        }

        $this->recalculateCartTotals($target);
    }

    /**
     * Get active cart for a given user (or null).
     */
    public function getActiveCartForUser(User $user): ?Cart
    {
        return Cart::query()
            ->active()
            ->forUser($user->id)
            ->with('items.buyable')
            ->first();
    }

    /**
     * Recalculate line totals for a CartItem.
     */
    protected function recalculateItem(CartItem $item): void
    {
        $lineTotal = ((float) $item->unit_price * $item->quantity) - (float) $item->discount_amount;
        $item->total = max($lineTotal, 0);
        $item->save();
    }

    /**
     * Recalculate aggregated totals for a cart.
     */
    protected function recalculateCartTotals(Cart $cart): void
    {
        $cart->loadMissing('items');

        $subtotal = 0.0;
        $discountTotal = 0.0;
        $itemsCount = 0;

        foreach ($cart->items as $item) {
            $itemSubtotal = (float) $item->unit_price * $item->quantity;
            $subtotal += $itemSubtotal;
            $discountTotal += (float) $item->discount_amount;
            $itemsCount += $item->quantity;
        }

        $cart->subtotal = $subtotal;
        $cart->discount_total = $discountTotal;
        $cart->grand_total = max($subtotal - $discountTotal, 0);
        $cart->items_count = $itemsCount;

        $cart->save();
    }
}
