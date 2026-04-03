<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_salary_payments', function (Blueprint $table) {
            $table->decimal('insurance_amount', 15, 2)->default(0)->after('amount_paid');
            $table->decimal('nssf', 15, 2)->default(0)->after('insurance_amount');
            $table->decimal('tax', 15, 2)->default(0)->after('nssf');
            // Whether employee has viewed and acknowledged payment
            $table->boolean('employee_acknowledged')->default(false)->after('status');

            // Optional digital signature image or base64 data
            $table->string('employee_signature')->nullable()->after('employee_acknowledged');

            // Timestamp when employee signed/acknowledged
            $table->timestamp('employee_signed_at')->nullable()->after('employee_signature');
        });
    }

    public function down(): void
    {
        Schema::table('employee_salary_payments', function (Blueprint $table) {
            $table->dropColumn(['employee_acknowledged', 'employee_signature', 'employee_signed_at']);
        });
    }
};
