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
            
            // Core Loan Relationship
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            
            // Group Context (Optional)
            $table->foreignId('group_center_id')->nullable()->constrained('group_centers')->onDelete('set null');
            
            // Schedule Details
            $table->integer('installment_number');
            $table->date('due_date');
            
            // Amounts Due (Amortization Plan)
            $table->decimal('principal_due', 15, 2)->default(0);
            $table->decimal('interest_due', 15, 2)->default(0);
            $table->decimal('fees_due', 15, 2)->default(0);
            $table->decimal('total_due', 15, 2)->default(0);
            
            // Status and Tracking
            $table->decimal('principal_outstanding', 15, 2)->default(0); // Remaining principal for this installment
            $table->decimal('amount_paid', 15, 2)->default(0); // Total amount paid against this installment
            
            // Allocated Paid Amounts (Captured via payment allocations)
            $table->decimal('principal_paid', 15, 2)->default(0);
            $table->decimal('interest_paid', 15, 2)->default(0);
            $table->decimal('fees_paid', 15, 2)->default(0);
            $table->decimal('total_paid', 15, 2)->default(0); // Duplicate of amount_paid, but kept for clarity
            
            $table->integer('days_late')->default(0);

            $table->enum('status', ['pending', 'paid', 'partial', 'overdue'])->default('pending');
            $table->date('paid_date')->nullable(); // Set when status becomes 'paid'

            // Auditing and Composite Key
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            
            // CORRECTED: Ensure installment number is unique only per loan
            $table->unique(['loan_id', 'installment_number']);
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