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
        Schema::create('daily_collections', function (Blueprint $table) {
             $table->id();
             $table->foreignId('client_loan_id')->nullable()->constrained('client_loans')->onDelete('cascade');
             $table->foreignId('group_center_id')->nullable()->constrained('group_centers')->onDelete('cascade');
             $table->date('date_of_payment');
             $table->decimal('amount_paid', 15, 2)->default(0);
             $table->decimal('total_preclosure', 15, 2)->default(0);
             $table->decimal('penalty_fee', 15, 2)->default(0)->comment('Late payment or fines');
             $table->boolean('first_date_pay')->default(false);
             $table->boolean('last_date_pay')->default(false);
             $table->enum('payment_method', ['bank_transfer', 'cash', 'cheque', 'mobile_money', 'direct_debit', 'card', 'other'])->default('cash');
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
        Schema::dropIfExists('daily_collections');
    }
};
