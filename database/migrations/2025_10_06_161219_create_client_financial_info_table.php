<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_financial_info', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            
            // Revenue
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->decimal('monthly_revenue', 15, 2)->nullable();
            $table->string('revenue_currency', 3)->default('TZS');

            // Expenses
            $table->decimal('monthly_expenses', 15, 2)->nullable();

            // Assets & Liabilities
            $table->decimal('total_assets', 15, 2)->nullable();
            $table->decimal('total_liabilities', 15, 2)->nullable();
            $table->decimal('net_worth', 15, 2)->nullable();

            // Banking
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number', 100)->nullable();
            $table->decimal('average_bank_balance', 15, 2)->nullable();

            // Loans
            $table->integer('existing_loans_count')->default(0);
            $table->decimal('total_existing_debt', 15, 2)->default(0);
            $table->decimal('monthly_debt_obligations', 15, 2)->default(0);

            // Ratios
            $table->decimal('debt_to_income_ratio', 5, 2)->nullable();
            $table->decimal('debt_service_coverage_ratio', 5, 2)->nullable();

            // Verification
            $table->boolean('verified')->default(false);
            $table->integer('verified_by_user_id')->nullable();
            $table->timestamp('verified_at')->nullable();

            // Period
            $table->integer('financial_year')->nullable();
            $table->date('as_of_date')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_financial_info');
    }
};

