<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id')
                ->constrained('carts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Polymorphic relationship to any buyable entity (Tire, Rim, etc.)
            $table->string('buyable_type');
            $table->unsignedBigInteger('buyable_id');

            $table->unsignedInteger('quantity')->default(1);

            // Price snapshot at the moment of adding to cart
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['cart_id']);
            $table->index(['buyable_type', 'buyable_id'], 'cart_items_buyable_idx');
            $table->unique(
                ['cart_id', 'buyable_type', 'buyable_id'],
                'cart_items_cart_buyable_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
