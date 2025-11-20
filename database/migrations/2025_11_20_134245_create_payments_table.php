<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethodType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('provider', 50)->default('wompi');

            // Reference used to link with payment gateway
            $table->string('reference', 100)->unique();

            // Main provider payment/transaction id (e.g. Wompi transaction id)
            $table->string('provider_payment_id', 150)->nullable();

            // Payment status
            $table->string('status', 32)->default(PaymentStatus::PENDING->value);

            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('COP');

            // card, pse, nequi, etc.
            $table->string('payment_method_type', 50)->nullable();

            // Store provider-specific payload (created transaction, etc.)
            $table->json('provider_payload')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status'], 'payments_order_status_idx');
            $table->index('provider_payment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
