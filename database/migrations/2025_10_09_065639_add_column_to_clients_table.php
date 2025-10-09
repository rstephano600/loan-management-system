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
        Schema::table('clients', function (Blueprint $table) {
            $table->decimal('business_capital', 15, 2)->default(0)->after('business_name');
            $table->decimal('business_income', 15, 2)->default(0)->after('business_capital');
            $table->string('business_location')->nullable()->after('business_income');
            $table->string('partner_in_business')->nullable()->after('business_location');
            $table->decimal('months_in_business', 4, 2)->nullable()->after('years_in_business');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
};
