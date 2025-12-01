<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use App\Services\Order\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService,
    ) {}

    /**
     * GET /checkout
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $cartToken = $request->cookie('cart_token');

        $cart = $this->cartService->getOrCreateCart($cartToken, $user);

        return view('checkout.index', [
            'cart' => $cart->load('items.buyable'),
        ]);
    }

    /**
     * POST /checkout
     * MVP: stub para que el formulario no rompa, luego lo conectamos a OrderService.
     */
    public function store(Request $request): RedirectResponse
    {
        // Lo rellenaremos en la siguiente iteración.
        return redirect()->route('checkout.index')
            ->with('status', 'Checkout en construcción (MVP).');
    }
}
