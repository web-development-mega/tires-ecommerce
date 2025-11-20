<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderPaymentRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderPaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {
    }

    /**
     * POST /api/orders/{order}/payments
     *
     * Create a payment intent for a given order (Wompi).
     */
    public function store(CreateOrderPaymentRequest $request, Order $order): JsonResponse
    {
        $payment = $this->paymentService->createPaymentForOrder(
            $order,
            $request->paymentData()
        );

        // Aquí normalmente devolverías datos para que el front sepa
        // cómo redirigir al checkout de Wompi / mostrar widget, etc.
        return response()->json([
            'payment' => [
                'id'        => $payment->id,
                'reference' => $payment->reference,
                'status'    => $payment->status->value,
                'amount'    => (float) $payment->amount,
                'currency'  => $payment->currency,
                // Podrías incluir 'wompi_checkout_url' si lo generas en PaymentService
            ],
            'order' => OrderResource::make($order),
        ], Response::HTTP_CREATED);
    }
}
