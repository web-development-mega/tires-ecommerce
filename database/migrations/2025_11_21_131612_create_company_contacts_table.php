<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_contacts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('position', 150)->nullable(); // cargo: Jefe de mantenimiento, Compras, etc.

            $table->boolean('is_primary')->default(false);

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['company_id', 'is_primary'], 'company_contacts_company_primary_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_contacts');
    }
};
