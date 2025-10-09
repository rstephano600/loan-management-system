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
            $table->dropColumn('business_capital');
            $table->dropColumn('business_income');
            $table->decimal('business_capital', 15, 2)->nullable()->default(0)->after('business_name');
            $table->decimal('business_income', 15, 2)->nullable()->default(0)->after('business_capital');
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
