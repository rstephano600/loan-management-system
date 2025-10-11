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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            // 🔹 Relationships
            $table->foreignId('group_center_id')->nullable()->constrained('group_centers')->nullOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('collection_officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('loan_category_id')->nullable()->constrained('loan_categories')->nullOnDelete();

            // 🔹 Core Loan Information
            $table->string('loan_number')->unique();
            $table->decimal('amount_requested', 15, 2)->default(0);
            $table->decimal('client_payable_frequency', 15, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'active', 'completed', 'defaulted', 'closed'])->default('pending');
            
            // 🔹 Approval & Fees
            $table->decimal('amount_disbursed', 15, 2)->default(0);
            $table->decimal('membership_fee', 15, 2)->default(0);
            $table->decimal('insurance_fee', 15, 2)->default(0);
            $table->decimal('officer_visit_fee', 15, 2)->default(0);
            $table->decimal('other_fee', 15, 2)->default(0);
            $table->decimal('penalty_fee', 15, 2)->default(0);
            $table->decimal('preclosure_fee', 15, 2)->default(0);

            // 🔹 Interest and Terms
            $table->decimal('interest_rate', 8, 2)->nullable()->default(20);
            $table->decimal('interest_amount', 15, 2)->nullable()->default(0);
            $table->enum('repayment_frequency', ['daily', 'weekly', 'bi_weekly', 'monthly', 'quarterly'])->default('daily');
            $table->integer('max_term_days')->nullable()->default(0);
            $table->integer('max_term_months')->nullable()->default(0);
            $table->integer('total_days_due')->nullable()->default(0);
            $table->decimal('principal_due', 15, 2)->default(0);
            $table->decimal('interest_due', 15, 2)->nullable()->default(0);
            $table->date('disbursement_date')->nullable();

            $table->decimal('total_due', 15, 2)->virtualAs('principal_due + interest_due');

            // repayments
            $table->decimal('amount_paid', 15, 2)->nullable()->default(0);
            $table->decimal('membership_fee_paid', 15, 2)->nullable()->default(0);
            $table->decimal('insurance_fee_paid', 15, 2)->nullable()->default(0);
            $table->decimal('preclosure_fee_paid', 15, 2)->nullable()->default(0);
            $table->decimal('penalty_fee_paid', 15, 2)->nullable()->default(0)->comment('Late payment or fines');
            $table->decimal('other_fee_paid', 15, 2)->nullable()->default(0);

            // Date tracking and outstanding balance
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('days_left')->default(0);

            // 🔹 Closure Info
            $table->timestamp('closed_at')->nullable();
            $table->string('closure_reason')->nullable();

            // 1. Total Fees
            $table->decimal('total_fees', 15, 2)->virtualAs('membership_fee + other_fee + penalty_fee + preclosure_fee');

            // 2. Total Repayable Amount
            $table->decimal('total_repayable', 15, 2)->virtualAs('amount_disbursed + interest_amount + (membership_fee + insurance_fee + other_fee + penalty_fee + preclosure_fee)');

            // 3. Total Paid Amount
            $table->decimal('total_amount_paid', 15, 2)->virtualAs('penalty_fee_paid + preclosure_fee_paid + amount_paid + other_fee_paid + membership_fee_paid + insurance_fee_paid');

            // 5. Outstanding Balance
            $table->decimal('outstanding_balance', 15, 2)->virtualAs('((amount_disbursed + interest_amount + (membership_fee + insurance_fee + other_fee + penalty_fee + preclosure_fee)) - (amount_paid + preclosure_fee_paid + membership_fee + insurance_fee + penalty_fee_paid + other_fee_paid))');

            // loss and profit
            $table->decimal('total_profit', 15, 2)->virtualAs('(penalty_fee_paid + preclosure_fee_paid + amount_paid + other_fee_paid + membership_fee_paid + insurance_fee_paid) - amount_disbursed');
            
            // 🔹 System Fields
            $table->string('currency', 10)->default('TZS');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_new_client')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
