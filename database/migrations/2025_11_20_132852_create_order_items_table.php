<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Polymorphic buyable reference (Tire, Rim, etc.)
            $table->string('buyable_type');
            $table->unsignedBigInteger('buyable_id');

            // Snapshot fields
            $table->string('sku', 64)->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();

            $table->string('brand_name', 150)->nullable();
            $table->string('size_label', 50)->nullable(); // e.g. '205/55 R16'

            // Pricing
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['order_id']);
            $table->index(['buyable_type', 'buyable_id'], 'order_items_buyable_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
