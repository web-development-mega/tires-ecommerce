<?php

use App\Enums\CartStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('token', 64)->unique();

            // Cart status (backed by CartStatus enum)
            $table->string('status', 32)->default(CartStatus::ACTIVE->value);

            $table->char('currency', 3)->default('COP');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            $table->unsignedInteger('items_count')->default(0);

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status'], 'carts_user_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
