<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_location_service_type', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_location_id')
                ->constrained('service_locations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('service_type_id')
                ->constrained('service_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                ['service_location_id', 'service_type_id'],
                'service_loc_type_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_location_service_type');
    }
};
