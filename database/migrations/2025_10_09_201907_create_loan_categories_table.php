<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('amount_disbursed', 15, 2);
            $table->decimal('insurance_fee', 15, 2)->nullable()->default(0);
            $table->decimal('officer_visit_fee', 15, 2)->nullable()->default(0);
            $table->integer('interest_rate', 5, 2)->nullable()->default(20);
            $table->decimal('interest_amount', 5, 2)->nullable()->default(0);
            $table->enum('repayment_frequency', ['daily', 'weekly', 'bi_weekly', 'monthly', 'quarterly'])->default('daily');
            $table->integer('max_term_days')->nullable();
            $table->integer('max_term_months')->nullable();
            $table->integer('principal_due')->nullable();
            $table->decimal('interest_due', 15, 2)->nullable()->default(0);
            $table->decimal('total_due', 15, 2)->virtualAs('principal_due + interest_due');
            $table->decimal('repayable_amount', 15, 2)->virtualAs('amount_disbursed + interest_amount');
            $table->integer('total_days_due')->nullable()->default(0);
            $table->string('currency')->nullable()->default('TZS');
            $table->string('conditions')->nullable();
            $table->string('descriptions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_new_client')->default(true);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_categories');
    }
};
