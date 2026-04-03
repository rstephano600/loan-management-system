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
        Schema::create('employee_weekly_allowances', function (Blueprint $table) {
            $table->id();

            // Relationship to employee
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');

            // Week range (start and end)
            $table->date('week_start')->comment('Start date of the week')->nullable();
            $table->date('week_end')->comment('End date of the week')->nullable();

            // Allowance details
            $table->string('allowance_type')->default('transport')->comment('e.g., transport, meals, other');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('currency', 10)->default('TZS');

            // Optional description
            $table->text('description')->nullable();

            // Optional attachment (receipt or supporting doc)
            $table->string('attachment')->nullable();

            // Status tracking
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');

            // Record who created and updated
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            // When paid
            $table->date('payment_date')->nullable();

            $table->timestamps();

            // Optional: prevent duplicates per week per employee
            $table->unique(['employee_id', 'week_start', 'week_end'], 'employee_week_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_weekly_allowances');
    }
};
