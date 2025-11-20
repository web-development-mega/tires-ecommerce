<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WompiWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {
    }

    /**
     * POST /api/payments/wompi/webhook
     *
     * This is the endpoint configured in Wompi as webhook URL.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        // Aquí deberías validar la firma de Wompi (X-Signature) con tu llave
        // pero eso depende de tus env vars y preferencias. Por ahora lo dejamos anotado.
        //
        // Ejemplo (pseudo):
        // $signature = $request->header('X-Signature');
        // $this->verifySignature($signature, $payload);

        $payment = $this->paymentService->handleWompiWebhook($payload);

        if (! $payment) {
            return response()->json([
                'message' => 'Payment not found or invalid payload',
            ], Response::HTTP_OK); // Wompi espera 2xx
        }

        return response()->json([
            'message' => 'Webhook processed',
            'payment_id' => $payment->id,
            'status' => $payment->status->value,
        ], Response::HTTP_OK);
    }
}
