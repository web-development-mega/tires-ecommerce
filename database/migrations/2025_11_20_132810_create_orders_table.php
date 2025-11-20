<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderStatus;
use App\Enums\OrderDeliveryType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('cart_id')
                ->nullable()
                ->constrained('carts')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('order_number', 50)->unique();

            // Status (backed by OrderStatus enum)
            $table->string('status', 32)->default(OrderStatus::PENDING_PAYMENT->value);

            $table->char('currency', 3)->default('COP');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('shipping_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            $table->unsignedInteger('items_count')->default(0);

            // Customer data
            $table->string('customer_first_name', 100)->nullable();
            $table->string('customer_last_name', 100)->nullable();
            $table->string('customer_email', 150)->nullable();
            $table->string('customer_phone', 50)->nullable();

            $table->string('document_type', 20)->nullable();   // CC, NIT, CE, etc.
            $table->string('document_number', 50)->nullable();

            // Fulfillment
            $table->string('delivery_type', 32)->default(OrderDeliveryType::HOME_DELIVERY->value);

            // Shipping address (for home delivery)
            $table->string('shipping_address_line1', 255)->nullable();
            $table->string('shipping_address_line2', 255)->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_state', 100)->nullable();
            $table->string('shipping_postal_code', 20)->nullable();
            $table->string('shipping_country', 2)->default('CO');

            // For service location / workshop fulfillment (we will model this later)
            $table->unsignedBigInteger('service_location_id')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status'], 'orders_user_status_idx');
            $table->index('order_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
