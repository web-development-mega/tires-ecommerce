<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Tire;
use App\Services\Cart\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    /**
     * GET /cart
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $cartToken = $request->cookie('cart_token');

        $cart = $this->cartService->getOrCreateCart($cartToken, $user);

        // Si no había token, lo generamos y luego pondremos la cookie (lo haremos en middleware o aquí)
        return view('cart.index', [
            'cart' => $cart->load('items.buyable'),
        ]);
    }

    /**
     * POST /cart
     * MVP: agregar llanta al carrito.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tire_id' => ['required', 'integer', 'exists:tires,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $user = $request->user();
        $cartToken = $request->cookie('cart_token');

        $cart = $this->cartService->getOrCreateCart($cartToken, $user);

        $tire = Tire::query()
            ->active()
            ->findOrFail($data['tire_id']);

        $quantity = $data['quantity'] ?? 1;

        $cart = $this->cartService->addTireToCart($cart, $tire, $quantity);

        // Set cookie de cart_token si no existía
        $response = redirect()->route('cart.index')
            ->with('status', 'Producto agregado al carrito.');

        if (! $cartToken) {
            $response->cookie('cart_token', $cart->token, 60 * 24 * 30); // 30 días
        }

        return $response;
    }

    /**
     * Más adelante implementamos update() y destroy() para el carrito web.
     */
    public function update(Request $request, CartItem $item): RedirectResponse
    {
        // Stub para que la ruta exista, luego lo completamos.
        return redirect()->route('cart.index');
    }

    public function destroy(Request $request, CartItem $item): RedirectResponse
    {
        // Stub para que la ruta exista, luego lo completamos.
        return redirect()->route('cart.index');
    }
}
