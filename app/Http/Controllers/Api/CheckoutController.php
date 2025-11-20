<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    /**
     * POST /api/checkout
     *
     * Creates an order from an existing cart.
     */
    public function store(CheckoutRequest $request): JsonResponse
    {
        $user      = $request->user(); // null for guests
        $cartToken = $request->cartToken();

        $cartQuery = Cart::query()
            ->active()
            ->with('items.buyable');

        if ($cartToken) {
            $cartQuery->where('token', $cartToken);

            if ($user) {
                // Ensure cart belongs to this user or is still guest cart
                $cartQuery->where(function ($q) use ($user) {
                    $q->whereNull('user_id')
                      ->orWhere('user_id', $user->id);
                });
            }
        } elseif ($user) {
            // No token, but authenticated user → use user's active cart
            $cartQuery->where('user_id', $user->id);
        } else {
            return response()->json([
                'message' => 'Cart token is required for guest checkout.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $cart = $cartQuery->firstOrFail();

        $order = $this->orderService->createFromCart(
            $cart,
            $request->checkoutData()
        );

        return OrderResource::make($order->load('items'))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
