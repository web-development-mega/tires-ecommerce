<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')
                ->constrained('brands')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('tire_size_id')
                ->constrained('tire_sizes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('sku', 64)->unique();
            $table->string('name');     // Full commercial name
            $table->string('slug')->unique();
            $table->string('pattern')->nullable(); // Pattern/trade name

            // Load / speed
            $table->unsignedSmallInteger('load_index')->nullable();
            $table->string('speed_rating', 3)->nullable(); // e.g. 'H', 'V'

            // UTQG
            $table->unsignedSmallInteger('utqg_treadwear')->nullable();
            $table->string('utqg_traction', 2)->nullable();    // 'AA', 'A', 'B', 'C'
            $table->string('utqg_temperature', 2)->nullable(); // 'A', 'B', 'C'

            // EU-style label (we can adapt for local needs)
            $table->string('fuel_efficiency_grade', 2)->nullable(); // 'A'..'G'
            $table->string('wet_grip_grade', 2)->nullable();        // 'A'..'G'
            $table->unsignedTinyInteger('noise_db')->nullable();
            $table->string('noise_class', 2)->nullable();           // 'A', 'B', 'C'

            // General attributes
            $table->string('country_of_origin', 100)->nullable();
            $table->string('usage', 50)->nullable(); // cast to TireUsage enum

            $table->boolean('is_runflat')->default(false);
            $table->boolean('is_all_terrain')->default(false);
            $table->boolean('is_highway')->default(false);
            $table->boolean('is_winter')->default(false);
            $table->boolean('is_summer')->default(false);

            // Pricing
            $table->decimal('base_price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->char('currency', 3)->default('COP');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['brand_id', 'tire_size_id'], 'tires_brand_size_idx');
            $table->index('is_active');
            $table->index('base_price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tires');
    }
};
