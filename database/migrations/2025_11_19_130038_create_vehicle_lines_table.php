<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_brand_id')
                ->constrained('vehicle_brands')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('name', 150);       // e.g. 'Duster'
            $table->string('slug', 150);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(
                ['vehicle_brand_id', 'slug'],
                'vehicle_lines_brand_slug_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_lines');
    }
};
