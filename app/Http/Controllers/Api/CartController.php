<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Tire;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    /**
     * GET /api/cart
     * Resolve or create a cart for the current user / token.
     */
    public function show(Request $request): JsonResponse
    {
        $cartToken = $request->query('cart_token');
        $user = $request->user(); // null if not authenticated

        $cart = $this->cartService->getOrCreateCart($cartToken, $user);

        return CartResource::make($cart->load('items.buyable'))
            ->additional([
                'meta' => [
                    'cart_token' => $cart->token,
                ],
            ])
            ->response();
    }

    /**
     * POST /api/cart/items
     * Add a Tire to cart.
     */
    public function addItem(AddCartItemRequest $request): JsonResponse
    {
        $user = $request->user();
        $cartToken = $request->cartToken();

        $cart = $this->cartService->getOrCreateCart($cartToken, $user);

        $tire = Tire::query()
            ->active()
            ->findOrFail($request->tireId());

        $cart = $this->cartService->addTireToCart($cart, $tire, $request->quantity());

        return CartResource::make($cart->load('items.buyable'))
            ->additional([
                'meta' => [
                    'cart_token' => $cart->token,
                ],
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * PUT /api/cart/items/{item}
     * Update quantity of an item.
     */
    public function updateItem(
        UpdateCartItemRequest $request,
        CartItem $item
    ): JsonResponse {
        $user = $request->user();
        $cartToken = $request->cartToken();

        // Basic security: ensure item belongs to user's / token's cart
        $this->assertItemBelongsToContext($item, $user?->id, $cartToken);

        $cart = $this->cartService->updateItemQuantity($item, $request->quantity());

        return CartResource::make($cart->load('items.buyable'))
            ->additional([
                'meta' => [
                    'cart_token' => $cart->token,
                ],
            ])
            ->response();
    }

    /**
     * DELETE /api/cart/items/{item}
     */
    public function removeItem(Request $request, CartItem $item): JsonResponse
    {
        $user = $request->user();
        $cartToken = $request->input('cart_token') ?? $request->query('cart_token');

        $this->assertItemBelongsToContext($item, $user?->id, $cartToken);

        $cart = $this->cartService->removeItem($item);

        return CartResource::make($cart->load('items.buyable'))
            ->additional([
                'meta' => [
                    'cart_token' => $cart->token,
                ],
            ])
            ->response();
    }

    /**
     * Ensure a CartItem belongs to the given user or cart token.
     */
    protected function assertItemBelongsToContext(
        CartItem $item,
        ?int $userId,
        ?string $cartToken
    ): void {
        $cart = $item->cart;

        $ownedByUser = $userId && $cart->user_id === $userId;
        $ownedByToken = $cartToken && $cart->token === $cartToken;

        if (! $ownedByUser && ! $ownedByToken) {
            abort(Response::HTTP_FORBIDDEN, 'Cart item does not belong to this cart.');
        }
    }
}
