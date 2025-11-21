<?php

use App\Enums\CompanyContractStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_contracts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('name', 150);
            $table->string('code', 50)->unique();

            $table->string('status', 32)->default(CompanyContractStatus::DRAFT->value);

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Condiciones financieras específicas del contrato
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->unsignedSmallInteger('payment_terms_days')->nullable(); // si null, usa los de la empresa

            $table->text('notes')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['company_id', 'status'], 'company_contracts_company_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_contracts');
    }
};
