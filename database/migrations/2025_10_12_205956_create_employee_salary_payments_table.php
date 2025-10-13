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
        Schema::create('employee_salary_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_salary_id')->constrained('employee_salaries')->onDelete('cascade');
            $table->decimal('amount_paid', 15, 2); // amount actually paid
            $table->date('payment_date'); // date of payment
            $table->enum('payment_method', ['bank_transfer', 'cash', 'cheque', 'mobile_money', 'direct_debit', 'card', 'other'])->default('cash');
            $table->string('currency', 10)->default('TZS');
            $table->string('status')->default('paid'); // paid, pending

            $table->string('attachment')->nullable(); // optional receipt or document

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_payments');
    }
};
