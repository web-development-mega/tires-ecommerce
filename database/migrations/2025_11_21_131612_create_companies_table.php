<?php

use App\Enums\CompanyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);        // Nombre comercial
            $table->string('legal_name', 255)->nullable(); // Razón social
            $table->string('tax_id', 50)->nullable();      // NIT
            $table->string('email', 150)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('website', 150)->nullable();

            $table->string('type', 50)->default(CompanyType::FLEET->value);

            // Billing address
            $table->string('billing_address_line1', 255)->nullable();
            $table->string('billing_address_line2', 255)->nullable();
            $table->string('billing_city', 100)->nullable();
            $table->string('billing_state', 100)->nullable();
            $table->string('billing_postal_code', 20)->nullable();
            $table->string('billing_country', 2)->default('CO');

            // Crédito y condiciones
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->unsignedSmallInteger('payment_terms_days')->default(0); // 0 = contado

            $table->boolean('is_active')->default(true);

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['type', 'is_active'], 'companies_type_active_idx');
            $table->index('tax_id', 'companies_tax_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
