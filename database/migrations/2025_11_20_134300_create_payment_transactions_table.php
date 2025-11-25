<?php

use App\Enums\PaymentTransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_id')
                ->constrained('payments')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('provider', 50)->default('wompi');

            // For Wompi this could be "transaction.updated", or the status name
            $table->string('provider_event', 100)->nullable();

            $table->string('status', 32)->default(PaymentTransactionStatus::PENDING->value);

            // Full raw payload from webhook or provider response
            $table->json('raw_payload')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['payment_id', 'status'], 'payment_transactions_payment_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
