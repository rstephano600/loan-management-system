<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('repayment_schedule_id')->nullable()->constrained()->onDelete('set null');

            // ✅ New field for group centre
            $table->foreignId('group_center_id')->nullable()->constrained()->onDelete('set null');

            // Payment details
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->decimal('principal_component', 15, 2)->default(0);
            $table->decimal('interest_component', 15, 2)->default(0);
            $table->decimal('fees_component', 15, 2)->default(0);
            $table->decimal('penalty_component', 15, 2)->default(0);

            // Payment info
            $table->string('payment_method')->nullable(); 
            $table->string('reference_number')->nullable(); 
            $table->string('receipt_number')->nullable()->unique();
            $table->text('remarks')->nullable();

            // Status
            $table->enum('status', ['pending', 'confirmed', 'reversed'])->default('confirmed');

            // Audit
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};
