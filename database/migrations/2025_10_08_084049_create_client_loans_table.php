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
        Schema::create('client_loans', function (Blueprint $table) {
              $table->id();
            //   identification details for client
              $table->integer('group_id')->nullable();
              $table->integer('client_id')->nullable();
              $table->integer('group_center_id')->nullable();

              // Loan Details
              $table->string('loan_number')->unique();
              $table->decimal('amount_requested', 15, 2)->default(0);
              $table->decimal('payable_frequency', 15, 2)->default(0);
              $table->enum('repayment_frequency', ['daily', 'weekly', 'bi_weekly', 'monthly', 'yearly', 'quarterly'])->default('daily');

              // approval
              $table->decimal('amount_disbursed', 15, 2)->default(0);
              $table->decimal('membership_fee', 15, 2)->nullable()->default(0);
              $table->decimal('insurance_fee', 15, 2)->nullable()->default(0);
              $table->decimal('officer_visit_fee', 15, 2)->nullable()->default(0);
              $table->decimal('loan_fee', 15, 2)->default(0);
              $table->decimal('other_fee', 15, 2)->default(0);
              $table->decimal('interest_rate', 5, 2)->default(0);
              $table->decimal('interest_amount', 15, 2)->default(0);
              $table->decimal('total_payable', 15, 2)->virtualAs('amount_disbursed + interest_amount + other_fee + loan_fee');

            //   payment frequency
              $table->integer('principal_days_due')->nullable();
              $table->integer('principal_week_due')->nullable();
              $table->integer('principal_months_due')->nullable();
              $table->integer('principal_years_due')->nullable();

            //   interest due frequency
              $table->integer('principal_due')->nullable();
              $table->decimal('interest_due', 15, 2)->default(0);
              $table->decimal('fees_due', 15, 2)->default(0);
              $table->decimal('total_due', 15, 2)->virtualAs();
              // repayment
              $table->decimal('amount_paid', 15, 2)->default(0);
              $table->decimal('outstanding_balance', 15, 2)->default(0);
              $table->decimal('total_preclosure', 15, 2)->default(0);
              $table->decimal('penalty_fee', 15, 2)->default(0)->comment('Late payment or fines');
              $table->decimal('total_amount_paid', 15, 2)->virtualAs('penalty_fee + amount_paid + total_preclosure');

              $table->decimal('profit_loss_amount', 15, 2)->virtualAs('total_amount_paid - amount_disbursed');

              // Date tracking
              $table->date('start_date')->nullable();
              $table->date('end_date')->nullable();
              $table->integer('days_left')->default(0);

              $table->timestamp('closed_at')->nullable();
              $table->string('closure_reason')->nullable();

              // Loan status
              $table->enum('status', ['pending', 'approved', 'disbursed', 'completed', 'defaulted'])->default('pending');
              $table->text('remarks')->nullable();

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
        Schema::dropIfExists('client_loans');
    }
};
