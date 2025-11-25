<?php

namespace App\Services\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethodType;
use App\Enums\PaymentStatus;
use App\Enums\PaymentTransactionStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PaymentService
{
    /**
     * Create a payment intent for a given order.
     *
     * $data can include:
     * - payment_method_type
     * - amount (optional override, defaults to order grand_total)
     * - meta
     */
    public function createPaymentForOrder(Order $order, array $data = []): Payment
    {
        if ($order->status !== OrderStatus::PENDING_PAYMENT) {
            throw new InvalidArgumentException('Order is not pending payment.');
        }

        $amount = $data['amount'] ?? $order->grand_total;

        if ($amount <= 0) {
            throw new InvalidArgumentException('Payment amount must be greater than zero.');
        }

        $paymentMethodType = isset($data['payment_method_type'])
            ? PaymentMethodType::from($data['payment_method_type'])
            : PaymentMethodType::CARD;

        return DB::transaction(function () use ($order, $amount, $paymentMethodType, $data): Payment {
            $reference = $this->generatePaymentReference($order);

            $payment = new Payment([
                'order_id' => $order->id,
                'provider' => 'wompi',
                'reference' => $reference,
                'status' => PaymentStatus::PENDING,
                'amount' => $amount,
                'currency' => $order->currency ?? 'COP',
                'payment_method_type' => $paymentMethodType,
                'meta' => $data['meta'] ?? null,
            ]);

            $payment->save();

            // Aquí es donde, en la vida real, llamarías a la API de Wompi
            // (Checkout / Payment Link / etc.) usando $reference y $amount.
            // Ejemplo:
            // $response = $this->wompiClient->createTransaction([...]);
            // $payment->provider_payment_id = $response['data']['id'] ?? null;
            // $payment->provider_payload = $response;
            // $payment->save();

            return $payment->fresh();
        });
    }

    protected function generatePaymentReference(Order $order): string
    {
        // Simple example: "ORDER-{order_number}-{random}"
        $random = Str::upper(Str::random(6));

        return 'ORDER-'.$order->order_number.'-'.$random;
    }

    /**
     * Handle Wompi webhook payload.
     *
     * Expecting $payload to include:
     * - "data" => ["transaction" => [...] ]
     * - "event" => string
     */
    public function handleWompiWebhook(array $payload): ?Payment
    {
        $event = $payload['event'] ?? null;
        $transaction = $payload['data']['transaction'] ?? null;

        if (! $transaction) {
            return null;
        }

        $wompiTransactionId = $transaction['id'] ?? null;
        $reference = $transaction['reference'] ?? null;
        $status = $transaction['status'] ?? null;
        $paymentMethodType = $transaction['payment_method_type'] ?? null;
        $amountInCents = $transaction['amount_in_cents'] ?? null;
        $currency = $transaction['currency'] ?? 'COP';

        // Wompi amounts are usually in cents
        $amount = $amountInCents !== null ? $amountInCents / 100 : null;

        return DB::transaction(function () use (
            $event,
            $wompiTransactionId,
            $reference,
            $status,
            $paymentMethodType,
            $amount,
            $currency,
            $payload
        ): ?Payment {
            if (! $reference) {
                return null;
            }

            /** @var Payment|null $payment */
            $payment = Payment::query()
                ->where('reference', $reference)
                ->first();

            if (! $payment) {
                return null;
            }

            // Create PaymentTransaction log
            $txStatus = $this->mapWompiStatusToTransactionStatus($status);

            $paymentTransaction = new PaymentTransaction([
                'payment_id' => $payment->id,
                'provider' => 'wompi',
                'provider_event' => $event ?? $status,
                'status' => $txStatus,
                'raw_payload' => $payload,
            ]);

            $paymentTransaction->save();

            // Update Payment
            $payment->provider_payment_id = $wompiTransactionId;
            $payment->provider_payload = $payload;
            $payment->payment_method_type = $paymentMethodType
                ? $this->mapWompiPaymentMethodType($paymentMethodType)
                : $payment->payment_method_type;

            if ($amount !== null) {
                $payment->amount = $amount;
                $payment->currency = $currency;
            }

            $payment->status = $this->mapWompiStatusToPaymentStatus($status);
            $payment->save();

            // Update Order status if needed
            $order = $payment->order;

            if ($payment->status === PaymentStatus::APPROVED) {
                $order->status = OrderStatus::PAID;
                $order->save();
            } elseif ($payment->status === PaymentStatus::DECLINED) {
                $order->status = OrderStatus::PAYMENT_FAILED;
                $order->save();
            }

            return $payment->fresh('order');
        });
    }

    protected function mapWompiStatusToPaymentStatus(?string $wompiStatus): PaymentStatus
    {
        // Wompi statuses: PENDING, APPROVED, DECLINED, VOIDED, ERROR
        return match (strtoupper((string) $wompiStatus)) {
            'APPROVED' => PaymentStatus::APPROVED,
            'DECLINED' => PaymentStatus::DECLINED,
            'VOIDED' => PaymentStatus::CANCELLED,
            'ERROR' => PaymentStatus::ERROR,
            default => PaymentStatus::PENDING,
        };
    }

    protected function mapWompiStatusToTransactionStatus(?string $wompiStatus): PaymentTransactionStatus
    {
        return match (strtoupper((string) $wompiStatus)) {
            'APPROVED' => PaymentTransactionStatus::SUCCEEDED,
            'DECLINED', 'VOIDED', 'ERROR' => PaymentTransactionStatus::FAILED,
            default => PaymentTransactionStatus::PENDING,
        };
    }

    protected function mapWompiPaymentMethodType(string $type): PaymentMethodType
    {
        return match (strtolower($type)) {
            'card' => PaymentMethodType::CARD,
            'pse' => PaymentMethodType::PSE,
            'nequi' => PaymentMethodType::NEQUI,
            'daviplata' => PaymentMethodType::DAVIPLATA,
            default => PaymentMethodType::OTHER,
        };
    }
}
