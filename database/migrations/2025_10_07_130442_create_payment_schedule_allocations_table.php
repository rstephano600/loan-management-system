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
        Schema::create('payment_schedule_allocations', function (Blueprint $table) {
            
            // Composite Key (Primary Key and Relationship)
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('repayment_schedule_id')->constrained('repayment_schedules')->onDelete('cascade');
            
            // Amounts Applied from the Payment to the Schedule Item
            $table->decimal('amount_applied', 15, 2);
            $table->decimal('principal_applied', 15, 2)->default(0);
            $table->decimal('interest_applied', 15, 2)->default(0);
            $table->decimal('fees_applied', 15, 2)->default(0);
            $table->decimal('penalty_applied', 15, 2)->default(0); // If penalties exist
            
            // Ensures a single payment line item can only be linked to a single schedule item once
            $table->primary(['payment_id', 'repayment_schedule_id'], 'payment_schedule_pk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_schedule_allocations');
    }
};