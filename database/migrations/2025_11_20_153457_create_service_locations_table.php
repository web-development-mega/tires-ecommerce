<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_locations', function (Blueprint $table) {
            $table->id();

            $table->string('name');          // Business name
            $table->string('slug')->unique();

            $table->string('phone', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('whatsapp', 50)->nullable();

            $table->string('address_line1', 255);
            $table->string('address_line2', 255)->nullable();

            // Municipality inside Valle de Aburrá
            $table->string('city', 100);             // e.g. "Medellín"
            $table->string('state', 100)->default('Antioquia');
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 2)->default('CO');

            // Optional coordinates for maps / logistics
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Opening hours JSON (flexible)
            $table->json('opening_hours')->nullable();

            $table->boolean('is_active')->default(true);

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['city', 'is_active'], 'service_locations_city_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_locations');
    }
};
