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
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_category_id')->constrained()->onDelete('restrict');
            $table->string('loan_number')->unique();
            $table->date('disbursement_date')->nullable();

            $table->enum('status', ['pending', 'approved', 'active', 'completed', 'defaulted', 'closed'])->default('pending');
            $table->date('first_payment_date')->nullable();
            $table->date('last_payment_date')->nullable();
            $table->date('next_payment_date')->nullable();
            $table->enum('delinquency_status', ['current', '1-30_days', '31-60_days', '61-90_days', '90+_days'])->default('current');

            $table->decimal('total_interest', 15, 2)->default(0);
            $table->decimal('total_payable', 15, 2)->default(0);

            $table->decimal('outstanding_principal', 15, 2)->default(0);
            $table->decimal('outstanding_interest', 15, 2)->default(0);
            $table->decimal('outstanding_fees', 15, 2)->default(0);
            $table->decimal('total_outstanding', 15, 2)->default(0);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->decimal('interest_paid', 15, 2)->default(0);
            $table->decimal('fees_paid', 15, 2)->default(0);

            $table->timestamp('closed_at')->nullable();
            $table->string('closure_reason')->nullable();

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
        Schema::dropIfExists('loans');
    }
};
