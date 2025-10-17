
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

            // Relationship to employee_salaries
            $table->foreignId('employee_salary_id')
                ->constrained('employee_salaries')
                ->onDelete('cascade');

            // Optional direct relation to employee for quick access
            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->onDelete('set null');

            $table->date('payment_date')->nullable();
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->string('currency', 10)->default('TZS');

            $table->enum('payment_method', ['bank_transfer', 'cash', 'cheque', 'mobile_money', 'direct_debit', 'card', 'other'])->default('cash');
            $table->string('reference_number')->nullable(); // e.g., transaction or receipt number
            $table->string('attachment')->nullable(); // optional proof of payment file

            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('confirmed');

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
