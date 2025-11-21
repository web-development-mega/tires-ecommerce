<?php

use App\Enums\PriceAdjustmentType;
use App\Enums\PriceTargetType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_price_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_contract_id')
                ->constrained('company_contracts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // scope del descuento
            $table->string('target_type', 50)->default(PriceTargetType::ALL_PRODUCTS->value);
            $table->unsignedBigInteger('target_id')->nullable(); // depende del target_type

            // tipo de producto (tire, rim, accessory, ev, etc.)
            $table->string('product_type', 50)->nullable();

            $table->string('adjustment_type', 50)->default(PriceAdjustmentType::PERCENTAGE->value);
            $table->decimal('value', 12, 2); // % o valor absoluto o precio final

            $table->unsignedInteger('min_quantity')->default(1);

            $table->boolean('is_active')->default(true);

            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['company_contract_id', 'is_active'], 'company_price_rules_contract_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_price_rules');
    }
};
