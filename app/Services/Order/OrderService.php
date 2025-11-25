<?php

namespace App\Services\Order;

use App\Enums\CartStatus;
use App\Enums\OrderDeliveryType;
use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderService
{
    /**
     * Create an Order from a Cart.
     *
     * @param  array  $data  Checkout data (customer, address, delivery, totals override if needed)
     */
    public function createFromCart(Cart $cart, array $data): Order
    {
        if ($cart->items()->count() === 0) {
            throw new InvalidArgumentException('Cannot create an order from an empty cart.');
        }

        if ($cart->status !== CartStatus::ACTIVE) {
            throw new InvalidArgumentException('Cart is not active.');
        }

        return DB::transaction(function () use ($cart, $data): Order {
            $cart->loadMissing('items.buyable');

            // Base totals from cart
            $subtotal = (float) $cart->subtotal;
            $discountTotal = (float) $cart->discount_total;
            $shippingTotal = (float) ($data['shipping_total'] ?? 0);
            $taxTotal = (float) ($data['tax_total'] ?? 0);
            $grandTotal = (float) ($data['grand_total'] ?? ($subtotal - $discountTotal + $shippingTotal + $taxTotal));
            $itemsCount = (int) $cart->items_count;

            /** @var Order $order */
            $order = new Order([
                'user_id' => $cart->user_id,
                'cart_id' => $cart->id,
                'currency' => $cart->currency ?? 'COP',

                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'shipping_total' => $shippingTotal,
                'tax_total' => $taxTotal,
                'grand_total' => $grandTotal,
                'items_count' => $itemsCount,

                'status' => OrderStatus::PENDING_PAYMENT,

                'customer_first_name' => $data['customer_first_name'] ?? null,
                'customer_last_name' => $data['customer_last_name'] ?? null,
                'customer_email' => $data['customer_email'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null,
                'document_type' => $data['document_type'] ?? null,
                'document_number' => $data['document_number'] ?? null,

                'delivery_type' => $data['delivery_type'] ?? OrderDeliveryType::HOME_DELIVERY->value,

                'shipping_address_line1' => $data['shipping_address_line1'] ?? null,
                'shipping_address_line2' => $data['shipping_address_line2'] ?? null,
                'shipping_city' => $data['shipping_city'] ?? null,
                'shipping_state' => $data['shipping_state'] ?? null,
                'shipping_postal_code' => $data['shipping_postal_code'] ?? null,
                'shipping_country' => $data['shipping_country'] ?? 'CO',

                'service_location_id' => $data['service_location_id'] ?? null,

                'meta' => $data['meta'] ?? null,
            ]);

            $order->save();

            // Create order items from cart items
            foreach ($cart->items as $cartItem) {
                $buyable = $cartItem->buyable;
                $sizeLabel = $buyable?->tireSize?->label ?? ($cartItem->meta['size'] ?? null);
                $brandName = $buyable?->brand?->name ?? null;

                $orderItem = new OrderItem([
                    'buyable_type' => $cartItem->buyable_type,
                    'buyable_id' => $cartItem->buyable_id,
                    'sku' => $buyable?->sku ?? null,
                    'name' => $buyable?->name ?? null,
                    'slug' => $buyable?->slug ?? null,
                    'brand_name' => $brandName,
                    'size_label' => $sizeLabel,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'discount_amount' => $cartItem->discount_amount,
                    'total' => $cartItem->total,
                    'meta' => $cartItem->meta,
                ]);

                $order->items()->save($orderItem);
            }

            // Mark cart as converted
            $cart->status = CartStatus::CONVERTED;
            $cart->save();

            return $order->fresh('items');
        });
    }
}
