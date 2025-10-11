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
        Schema::create('repayment_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_id');
            $table->string('installment_number')->unique()->nullable();
            $table->integer('due_day_number');
            $table->date('due_date')->nullable();

            // Amounts Due (Amortization Plan)
            $table->decimal('principal_due', 15, 2)->nullable()->default(0);
            $table->decimal('interest_due', 15, 2)->nullable()->default(0);
            $table->decimal('penalty_due', 15, 2)->nullable()->default(0);
            $table->decimal('total_due', 15, 2)->virtualAs('principal_due + interest_due + penalty_due');
            $table->decimal('total_proncipal_penalty_due', 15, 2)->virtualAs('principal_due + penalty_due');

            // Allocated Paid Amounts (Captured via payment allocations)
            $table->decimal('principal_paid', 15, 2)->nullable()->default(0);
            $table->decimal('interest_paid', 15, 2)->nullable()->default(0);
            $table->decimal('penalty_paid', 15, 2)->nullable()->default(0);
            $table->decimal('total_paid', 15, 2)->virtualAs('principal_paid + interest_paid + penalty_due');
            $table->date('paid_date')->nullable();
            $table->decimal('total_proncipal_penalty_paid', 15, 2)->virtualAs('principal_paid + penalty_paid');

            $table->enum('status', ['pending', 'paid', 'partial', 'overdue', 'closed'])->default('pending');
            $table->enum('payment_method', ['bank_transfer', 'cash', 'cheque', 'mobile_money', 'direct_debit', 'card', 'other'])->default('cash');
            
            $table->boolean('is_start_date')->default(false);
            $table->boolean('is_end_date')->default(false);
            $table->integer('days_left')->default(0);


            $table->integer('created_by');
            $table->boolean('is_paid')->default(false);
            $table->integer('paid_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayment_schedules');
    }
};
