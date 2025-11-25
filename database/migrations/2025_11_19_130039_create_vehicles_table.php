<?php

use App\Enums\VehicleType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vehicle_brand_id')
                ->constrained('vehicle_brands')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('vehicle_line_id')
                ->constrained('vehicle_lines')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('vehicle_version_id')
                ->nullable()
                ->constrained('vehicle_versions')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->unsignedSmallInteger('year'); // e.g. 2018

            // Vehicle type, backed by VehicleType enum
            $table->string('type', 32); // 'car', 'suv', 'motorcycle', etc.

            // Optional extra metadata (for filtros futuros)
            $table->string('engine', 64)->nullable();
            $table->string('fuel_type', 32)->nullable();
            $table->string('body_type', 32)->nullable(); // sedan, hatchback, suv, etc.

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['vehicle_brand_id', 'vehicle_line_id', 'vehicle_version_id', 'year'],
                'vehicles_unique_model'
            );

            $table->index(
                ['vehicle_brand_id', 'vehicle_line_id', 'year'],
                'vehicles_brand_line_year_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
