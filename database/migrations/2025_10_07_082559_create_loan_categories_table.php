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
            $table->string('name'); // e.g. "Business Loan"
            $table->decimal('interest_rate', 5, 2); // Annual interest rate (percentage)
            $table->integer('max_term_months');
            $table->integer('max_term_days')->nullable();
            $table->decimal('principal_amount', 15, 2);
            $table->string('currency')->default('TZS');
            $table->decimal('min_amount', 15, 2);
            $table->decimal('max_amount', 15, 2);
            $table->enum('repayment_frequency', ['daily', 'weekly', 'bi_weekly', 'monthly', 'quarterly'])->default('daily');
            $table->decimal('installment_amount', 15, 2);
            $table->decimal('total_repayable_amount', 15, 2);
            $table->string('conditions');
            $table->boolean('is_active')->default(true);
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
