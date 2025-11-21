<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('name', 150);
            $table->string('description', 255)->nullable();

            $table->boolean('is_active')->default(true);

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['company_id', 'is_active'], 'fleets_company_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
