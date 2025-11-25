<?php

use App\Enums\FitmentPosition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_tire_fitments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('tire_size_id')
                ->constrained('tire_sizes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // front / rear / both / spare (cast to FitmentPosition)
            $table->string('position', 16)->default('both');

            $table->boolean('is_oem')->default(true);
            $table->boolean('is_primary')->default(false);

            // Requisitos mínimos
            $table->unsignedSmallInteger('min_load_index')->nullable();
            $table->string('min_speed_rating', 3)->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['vehicle_id', 'tire_size_id', 'position'],
                'vehicle_tire_fitments_unique'
            );

            $table->index(
                ['vehicle_id', 'is_oem', 'is_primary'],
                'vehicle_tire_fitments_vehicle_flags_idx'
            );

            $table->index('tire_size_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_tire_fitments');
    }
};
