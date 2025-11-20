<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrackOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderTrackingController extends Controller
{
    /**
     * GET /api/orders/track?order_number=...&customer_email=... | document_number=...
     *
     * Public endpoint to track an order by number + customer info.
     */
    public function show(TrackOrderRequest $request): JsonResponse
    {
        $filters = $request->filters();

        $query = Order::query()
            ->where('order_number', $filters['order_number'] ?? null);

        if (! empty($filters['customer_email'])) {
            $query->where('customer_email', $filters['customer_email']);
        }

        if (! empty($filters['document_number'])) {
            $query->where('document_number', $filters['document_number']);
        }

        $order = $query
            ->with(['items', 'serviceLocation', 'payments'])
            ->firstOrFail();

        return OrderResource::make($order)->response();
    }
}
