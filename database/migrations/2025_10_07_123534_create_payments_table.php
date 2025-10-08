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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Core Relationships
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_center_id')->nullable()->constrained('group_centers')->onDelete('set null');
            
            // Transaction Details
            $table->string('payment_number')->unique();
            $table->date('payment_date');
            $table->decimal('payment_amount', 15, 2); // Gross amount received
            $table->string('currency')->default('TZS');
            
            // Payment Allocation (This is the breakdown of the Gross Amount)
            $table->decimal('principal_amount', 15, 2)->default(0);
            $table->decimal('interest_amount', 15, 2)->default(0);
            $table->decimal('fees_amount', 15, 2)->default(0);
            $table->decimal('penalty_amount', 15, 2)->default(0);
            
            // Method & Reference
            $table->enum('payment_method', ['bank_transfer', 'cash', 'cheque', 'mobile_money', 'direct_debit', 'card', 'other'])->default('cash');
            $table->string('transaction_reference')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            
            // Status and Remarks
            $table->enum('status', ['pending', 'completed', 'failed', 'reversed', 'cancelled'])->default('completed');
            $table->text('remarks')->nullable();
            
            // Auditing
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
        Schema::dropIfExists('payments');
    }
};