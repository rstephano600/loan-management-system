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
        Schema::table('employee_salary_payments', function (Blueprint $table) {

            $table->decimal('amount_paid', 15, 2)->nullable()->change(); // amount actually paid
            $table->date('payment_date')->nullable()->change(); // date of payment
            $table->decimal('bonus', 15, 2)->default(0)->nullable()->after('amount_paid'); // optional bonuses

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_salary_payments', function (Blueprint $table) {
            //
        });
    }
};
